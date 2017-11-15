var web = require("webpage");
var page = web.create();

page.onNavigationRequested = function(url, type, willNavigate, main) {
	if (main && url!=myurl && url.replace(/\/$/,"")!=myurl&& (type=="Other" || type=="Undefined") ) {
	// main = navigation in main frame; type = not by click/submit etc

		log("\tfollowing "+myurl+" redirect to "+url)
		myurl = url;
		page.close();
		renderPage(url); // rerun this function wit the new URL
	}
};

function login() {
	
}