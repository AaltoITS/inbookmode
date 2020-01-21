// ac.chm-update.js - JavaScript functions to enable the CUD (ChmUpDate) feature.
// 
// v.0.02: 16apr02 - fixed some bugs
// v.0.01: 03apr02 - the beginning
//
//===========================================================================

// check for any pre-existing "onload" code .. if found, append function
if(window.onload==null){
  window.onload=checkforupdates;
}
else{
  var fnOnload=window.onload;
  window.onload=function(){fnOnload();checkforupdates();}
}

function checkforupdates() {
  document.all.datatbl.onreadystatechange = checkDataState;
  var datafile = getcurchmname();
  if( datafile == "" ) { datafile = "SAMPLE-HELP.CHM"; }
  var curpath = getcurpath();
  datafile = datafile.substring(0,datafile.length-4) + ".cud";
  var dataurl = "file:///" + curpath + datafile;
  //dataUpdates.DataURL = "file:///" + curpath + "test.cud";
  //dataUpdates.Reset();
  //dataUpdates.AppendData = "True";
  dataUpdates.UseHeader = "True";
  dataUpdates.DataURL = dataurl;
  dataUpdates.Filter = "curfile=" + getcurfilename();
  dataUpdates.Sort = "group;title";
  //dataUpdates.Reset();

  try{
    dataUpdates.Reset();
  }
  catch(exception){
    //errnum=exception.number & 0xFFFF;
    //alert('ERROR:\n'+exception.description+'\nerrnum:'+errnum);
    return;
  }  
}

function checkDataState() {
  if (document.all.datatbl.readyState=="complete") {
    if( document.all.datatbl.rows.length > 0 ) {
      if( strtopicupdate != "0" ) {
        document.all.updatelbl.innerText=strtopicupdate;
      }
      document.all.dataupdate.style.display="";
    }
    //alert(document.all.datatbl.rows.length);
  }
}

