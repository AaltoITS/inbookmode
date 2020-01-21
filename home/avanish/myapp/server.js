var express = require('express');
var app = express();

var bodyParser = require('body-parser');
var path = require('path');
var url = require('url');
var http = require('http');
var api = require('./routes/api');
var requirejs = require('requirejs');

app.set('port', 3000);

app.use(express.json());
app.use(express.urlencoded());
app.use(express.bodyParser());
app.use(app.router);

// use bodyparser
app.use(bodyParser.urlencoded({ extended: true }))
app.use(bodyParser.json())

// development only
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

// POST Requests
app.post('/login', api.AuthInterface.login,api.AuthInterface.getLoggedInUser);
app.post('/getProjectByPoid', api.ServiceInterface.getProjectByPoid,api.ServiceInterface.getSerializerByName,api.ServiceInterface.download,api.ServiceInterface.downloadServlet,api.ServiceInterface.getProgress,api.ServiceInterface.elementProperties,api.ServiceInterface.cleanupLongAction);
app.post('/uploadifc', api.OtherInterface.login,api.OtherInterface.addProject,api.OtherInterface.checkinFromUrl);
//create http server
var server = http.createServer(app);

//http server config
server.setTimeout(600*1000);//unit is ms. 1s = 1000ms
server.listen(app.get('port'), function(req,res){
  console.log('Express server listening on port ' + app.get('port'));
});
