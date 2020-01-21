<!DOCTYPE html>
<html>
<head>
	<title>Projects of BIMservers</title>
</head>
<style type="text/css">
	
	body{
		display: block;
		margin: auto;
		text-align: center;
    	color: #230303;
	}
	#expserver h3{
		display: none;
	}

	#expserver ul li{
		list-style: none;		
		border: 1px solid #ccc;
	    padding: 3px;
	    margin: 5px 0;
	    font-size: 20px;
	}

	#expserver ul li a:hover {
		background: #7955482e;
    	color: #0f81f6;
	}

	#expserver ul{
		margin: auto 50px;
	    padding: 25px;
	    border: 1px solid #d5cfcf;
	}

	#expserver ul li a{
		text-decoration: none;
		background: #00bcd459;
		display: inherit;
		color: #555;
	}

	
</style>
<body>
<h1>Uploaded Project List From BIMservers</h1>
<div id="expserver"></div>
<!-- <div id="localhost8080"></div>
<div id="localhost8082"></div> -->
<!-- <h3>Other BIMserver</h3>
<a id="loadFromOtherBimServer" style="cursor: pointer">Load from other server</a>
<div id="other" style="display: none">
<input id="address"/>
<input id="username"/>
<input id="password" type="password"/>
<button id="loadProjectsBtn">Load projects</button>
<div id="otherProjects"></div>
</div> -->
<!-- <h3>glTF</h3>
<a href="docs/example_glTF.html">glTF</a>
<h3>Other</h3>
<a href="docs/example_testModel.html">Test Model</a> -->
<script>
document.addEventListener("DOMContentLoaded", function(event) {
	function loadFromBimserver(address, username, password, target) {
		target.innerHTML = "";
		var client = new BimServerClient(address);
		client.init(function(){
			var bimServerDiv = document.getElementById("bimserverdiv");
			var div = document.createElement("div");
			var h3 = document.createElement("h3");
			h3.textContent = address;
			div.appendChild(h3);
			var status = document.createElement("div");
			div.appendChild(status);
			var ul = document.createElement("ul");
			div.appendChild(ul);
			target.appendChild(div);
			
			status.textContent = "Logging in...";
			client.login(username, password, function(){
				status.textContent = "Getting all projects...";
				client.call("ServiceInterface", "getAllProjects", {
					onlyTopLevel: true,
					onlyActive: true
				}, function(projects){
					var totalFound = 0;
					projects.forEach(function(project){
						if (project.lastRevisionId != -1) {
							var li = document.createElement("li");
							var a = document.createElement("a");
							li.appendChild(a);
							a.textContent = project.name;
							a.setAttribute("href", "docs/example_BIMServer.html?address=" + encodeURIComponent(address) + "&token=" + client.token + "&poid=" + project.oid);
							ul.appendChild(li);
							totalFound++;
						}
					});
					if (totalFound == 0) {
						status.textContent = "No projects with revisions found on this server";
					} else {
						status.textContent = "";
					}
				});
			}, function(error){
				console.error(error);
				status.textContent = error.message;
			});
		});
	}
	
	function loadBimServerApi(apiAddress, loadUmd) {
		var p = new Promise(function(resolve, reject){
			if (loadUmd) {
				// TODO
				LazyLoad.js([Settings.getBimServerApiAddress() + "/bimserverapi.umd.js?_v=" + apiVersion], function(){
					window.BimServerClient = bimserverapi.default;
					window.BimServerApiPromise = bimserverapi.BimServerApiPromise;
					
					resolve(bimserverapi);
				});
			} else {
				// Using eval here, so we don't trip the browsers that don't understand "import"
				// The reason for using it this way is so we can develop this library and test it without having to transpile.
				// Obviously developers need to have a browser that understands "import" (i.e. a recent version of Chrome, Firefox etc...)
				
				// TODO One remaining problem here is that dependencies are not loaded with the "apiVersion" attached, so you need to have your browser on "clear cache" all the time
				
				var apiVersion = "1.5.92";
				
				var str = "import(\"" + apiAddress + "\" + \"/bimserverclient.js?_v=" + apiVersion + "\").then((bimserverapi) => {	window.BimServerClient = bimserverapi.default; window.BimServerApiPromise = bimserverapi.BimServerApiPromise; resolve(bimserverapi);});";
				eval(str);
			}
		});
		return p;
	}
	
	loadBimServerApi("http://inbookmode.aalto.fi:8087/apps/bimserverjavascriptapi", false).then(() => {
		try {
			loadFromBimserver("http://inbookmode.aalto.fi:8087", "avanish.chaurasiya@navteksolutions.com", "Aalto&navtek!@#", document.getElementById("expserver"));
		} catch (e) {
			console.log(e);
		}
		/*try {
			loadFromBimserver("http://localhost:8080", "admin@bimserver.org", "admin", document.getElementById("localhost8080"));
		} catch (e) {
			console.log(e);
		}*/
		/*try {
			loadFromBimserver("http://localhost:8082", "admin@bimserver.org", "admin", document.getElementById("localhost8082"));
		} catch (e) {
			console.log(e);
		}*/		
	});
	
	/*var loadLink = document.getElementById("loadFromOtherBimServer");
	loadLink.onclick = function(){
		document.getElementById("other").style.display = "block";
		if (localStorage.getItem("address") != null) {
			document.getElementById("address").value = localStorage.getItem("address");
			document.getElementById("username").value = localStorage.getItem("username");
			document.getElementById("password").value = localStorage.getItem("password");
		}
		document.getElementById("address").focus();
	};
	
	var loadProjectsBtn = document.getElementById("loadProjectsBtn");
	loadProjectsBtn.onclick = function(){
		var address = document.getElementById("address").value;
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;
		localStorage.setItem("address", address);
		localStorage.setItem("username", username);
		localStorage.setItem("password", password);
		loadFromBimserver(address, username, password, document.getElementById("otherProjects"));
	};*/
});
</script>
</body>
</html>