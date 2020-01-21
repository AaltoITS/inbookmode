var request = require('request');
//var request = require('xhr2');
var path = require('path');
var url = require('url');
var http = require('http');
var requirejs = require('requirejs');
var bodyParser = require('body-parser');
var base64 = require('file-base64');
var needle = require('needle');
var express = require('express');
var async = require('async');
var app = express();

var BimServerClient = require('../bimServerJS/bimserverclient');
var https = require('https');

// BIM Server Client Connection
var address = 'http://inbookmode.aalto.fi:8087';
var client = new BimServerClient(address);

app.use(bodyParser.urlencoded({extended: true})); // parse application/x-www-form-urlencoded
app.use(bodyParser.json()); // parse application/json

//Global variables for storing data from BIMServer
var projectId                   = 0;
var authToken                   = '';
var guidOfObject                = '';
var lastRoidOfProject           = 0;
var serializerOid               = 0;
var token                       = '';
var topId                       = 0;
var loggedInUserDetails         = {};
var makeProjectDetails         = {};
var IFCEntitiesOidArray;
var IFCElementPropertiesArray;
var obj;
var neobj;

var AuthInterface = {
    login: function(req, res, next) {
        var username = req.body.username;
        var password = req.body.password;
        client.login(username, password, function() {
            token = client.token;
            console.log(token);
            return next(); 
        }, function(err) {
            console.log(err);
            res.status(500).send(err);
        })
    },

    getLoggedInUser: function(req, res, next) {
        client.call('AuthInterface', 'getLoggedInUser',{ },function(data) {
            loggedInUserDetails.userType = data.userType;
            loggedInUserDetails.uoid     = data.oid;
            loggedInUserDetails.token    = token
            res.send(loggedInUserDetails);
        }, function(err) {
            console.log(err);
            res.send(err);
        })
    }
};

var ServiceInterface = {
    getProjectByPoid:function(req, res, next) {
        authToken       = req.body.token;
        guidOfObject    = req.body.guid;
        client.call('ServiceInterface', 'getProjectByPoid', {
            poid:req.body.poid
        }, function(data) {
            lastRoidOfProject = data.revisions[data.revisions.length-1];//We need the last revision in the array.
            return next();
        }, function(err) {
            console.log(err)
            res.status(500).send(err);
        });
    },

    getSerializerByName:function(req, res, next) {
        client.call('ServiceInterface', 'getSerializerByName', {
            serializerName:"Json (Streaming)"
        }, function(data) {
            serializerOid = data.oid;
            return next();
        }, function(err) {
            console.log(err)
            res.send(err);
        });
    },
    //2XnCNfbvb5mfgxvK1MPFfM
    //3do7k48PLEguBRpg1kJdJh
    download:function(req, res, next) {
        var query = "{\"guids\":[\""+guidOfObject+"\"], \"includes\":[\"validifc:AllProperties\"]}";
        client.call("ServiceInterface", "download", {
          roids: [lastRoidOfProject],
          query: query,
          serializerOid: serializerOid,
          //showOwn: true,
          sync: false
        }, function (topicId) {
            topId = topicId;
            return next();
        }, function(err) {
            console.log(err)
            res.send(err);
        });
    },

    downloadServlet:function(req, res, next) {
        var data = {token:token,topicId:topId,serializerOid:serializerOid};
        var httpRequestUrl = `http://inbookmode.aalto.fi:8087/download?token=${data.token}&serializerOid=${data.serializerOid}]&topicId=${data.topicId}`;
        //Seperate IFCEntities and IFCRels.
        IFCEntitiesOidArray=[];
        var IFCEntitiesArray=[];
        var IFCRelationshipsArray=[];
        //Make HTTP call by needle
        needle.get(httpRequestUrl, function(error, response) {
            if (!error && response.statusCode == 200)
            {
                //response.body is json type with structure of: {objects:[...]}. It is not base64 so different from the response data using api call.
                var IFCData = response.body;
                for(var i in IFCData.objects){
                    if(IFCData.objects[i]._t.includes('IfcRel',0)){
                        IFCRelationshipsArray.push(IFCData.objects[i]);
                    }else{
                        IFCEntitiesArray.push(IFCData.objects[i]);
                    }
                };
                for(var e in IFCEntitiesArray){
                    IFCEntitiesOidArray.push(IFCEntitiesArray[e]._i);
                };
                return next();
            }
            else if(error){
                console.log(error)
                res.send(error);
            }
        });
    },

    //Get progress state by topicId. e.g: download...
    getProgress:function(req, res, next) {
        client.call("NotificationRegistryInterface", "getProgress", {
            topicId: topId
        }, function(data){
            return next();
        }, function(err) {
            console.log(err)
            res.send(err);
        });
    },

    elementProperties:function(req, res, next) {
        IFCElementPropertiesArray=[];
        obj={};
        neobj;
        var count = 0;
        async.whilst(
            function() { return count < IFCEntitiesOidArray.length; },
            function(callback) {
                client.call('LowLevelInterface', 'getDataObjectByOid', {
                    roid:lastRoidOfProject,
                    oid:IFCEntitiesOidArray[count]
                }, function(data) {
                    IFCElementPropertiesArray.push(data);
                    /*if(data.oid == IFCEntitiesOidArray[0])
                    {
                        obj[data.type] = {};
                        obj[data.type]["GlobalId"] = data.guid;
                        obj[data.type]["name"] = data.name;
                        for (var i in data.values) {
                            if(data.values[i]["fieldName"] == "Tag")
                            {
                                obj[data.type]["Tag"] = data.values[i]["stringValue"];
                            }
                        };
                    }
                    else
                    {
                        var key, proValues;
                        if(data.name !="")
                        {
                            neobj = obj[data.name] = {};
                            //console.log(neobj);
                        }
                        else
                        {
                            //console.log(data.oid);
                          for (var i in data.values) {
                              if(data.values[i]["fieldName"] === "Name")
                              {
                                key = data.values[i]["stringValue"];
                              }

                              if(data.values[i]["fieldName"] === "NominalValue")
                              {
                                proValues = data.values[i]["stringValue"];
                              }
                          };
                          neobj[key] = proValues;
                        }
                    }*/
                    count+=1;
                    callback(null,null);
                }, function(err) {
                    console.log(err)
                    res.send(err);
                });
            },
            function (err) {
                if(err){
                    console.log(err)
                    res.send(err);
                }
                if(!err){
                    for (var q = 0; q < IFCElementPropertiesArray.length; q++) {
                        //console.log(IFCElementPropertiesArray[0]);
                        if(IFCElementPropertiesArray[q].oid == IFCEntitiesOidArray[0])
                        {
                            obj[IFCElementPropertiesArray[q].type] = {};
                            obj[IFCElementPropertiesArray[q].type]["GlobalId"] = IFCElementPropertiesArray[q].guid;
                            obj[IFCElementPropertiesArray[q].type]["name"] = IFCElementPropertiesArray[q].name;
                            for (var i in IFCElementPropertiesArray[q].values) {
                                if(IFCElementPropertiesArray[q].values[i]["fieldName"] == "OverallWidth")
                                {
                                    obj[IFCElementPropertiesArray[q].type]["OverallWidth"] = IFCElementPropertiesArray[q].values[i]["stringValue"];
                                }
                                if(IFCElementPropertiesArray[q].values[i]["fieldName"] == "OverallHeight")
                                {
                                    obj[IFCElementPropertiesArray[q].type]["OverallHeight"] = IFCElementPropertiesArray[q].values[i]["stringValue"];
                                }
                                if(IFCElementPropertiesArray[q].values[i]["fieldName"] == "Tag")
                                {
                                    obj[IFCElementPropertiesArray[q].type]["Tag"] = IFCElementPropertiesArray[q].values[i]["stringValue"];
                                }
                            };
                        }
                        else
                        {
                            var key, proValues;
                            if(IFCElementPropertiesArray[q].name !="")
                            {
                                neobj = obj[IFCElementPropertiesArray[q].name] = {};
                                //console.log(neobj);
                            }
                            else
                            {
                                //console.log(IFCElementPropertiesArray[q].oid);
                              for (var i in IFCElementPropertiesArray[q].values) {
                                  if(IFCElementPropertiesArray[q].values[i]["fieldName"] === "Name")
                                  {
                                    key = IFCElementPropertiesArray[q].values[i]["stringValue"];
                                  }

                                  if(IFCElementPropertiesArray[q].values[i]["fieldName"] === "NominalValue")
                                  {
                                    proValues = IFCElementPropertiesArray[q].values[i]["stringValue"];
                                  }
                              };
                              neobj[key] = proValues;
                            }
                        }
                    }
                    return next();
                }
            }
        );
    },

    //Cleanup the download cache
    cleanupLongAction:function(req, res) {
        client.call('ServiceInterface', 'cleanupLongAction', {
            topicId:topId
        }, function(data) {
            console.log('cleanupLongAction successfully!');
            res.send(obj);
        }, function(err) {
            console.log(err)
            res.send(err);
        });
    },

};

var OtherInterface ={
     login: function(req, res, next) {
        var username = 'avanish.chaurasiya@navteksolutions.com';
        var password = 'Aalto&navtek!@#';
        client.login(username, password, function() {
            token = client.token;
            console.log(token);
            return next(); 
        }, function(err) {
            console.log(err);
            res.status(500).send(err);
        })
    },

    addProject: function(req, res, next) {
        var projectName = req.body.projectName;
        client.call('ServiceInterface', 'addProject', {
            projectName: projectName,
            schema: 'ifc2x3tc1'
        }, function(data) {
            console.log(data); // the return data from bimsever is json type.
            projectId = data.oid;
            //res.end();
            return next();
        }, function(err) {
            console.log(err);
            res.send(err);
        });
    },

    /*checkin:function(req, res, next) {
        base64.encode(path.join(__dirname,'../public',req.files.IFCfile.name), function(err, base64String) {
            client.call('ServiceInterface', 'checkin', {
                poid:req.body.poid,
                comment:req.body.comment,
                deserializerOid: res.locals.deserializerOid,
                fileSize:req.files.IFCfile.size,//This three processed in server by just file address passed in .
                fileName:req.files.IFCfile.name,
                data:base64String,//calculate the file base64.
                merge:req.body.merge,
                sync:req.body.sync
            }, function(data) {
                console.log('checkin result: '+data); // the return data from bimsever is a topicId

            }, function(err) {
                console.log(err)
            });

        });
    },*/
    /*getProgress:function(req, res, next) {
        client.call("NotificationRegistryInterface", "getProgress", {
            topicId: topId
        }, function(data){
            return next();
        }, function(err) {
            console.log(err)
            res.send(err);
        });
    },*/

    checkinFromUrl: function(req, res, next) {
        client.call('ServiceInterface', 'checkinFromUrl', {
            poid: projectId,
            comment: "",
            deserializerOid: 917545,
            fileName: "",
            url: req.body.url,
            merge: "false",
            sync: "false"
        }, function(data) {
            if(data){
                console.log(projectId); // the return data from bimsever is json type.
                makeProjectDetails.projectId = projectId;
                //res.end();
                //return next()
                res.send(makeProjectDetails);
            }
        }, function(err) {
            console.log(err);
            res.send(err);
        });
    },

};

exports.AuthInterface = AuthInterface;
exports.ServiceInterface = ServiceInterface;
exports.OtherInterface = OtherInterface;
