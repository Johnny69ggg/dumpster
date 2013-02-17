var _express = require('express');
var _url = require('url');
var _parser = require('./feedparser');
var _dbcrud = require('dbcrud');
var _mysql = require('mysql');
var _http = require('http');
var _atomwriter = require('atom-writer');
var _xmlwriter = require('xml-writer')
var _processor = require('./processor');

var model = {
	polls: {
          url: { name: 'url', type: 'varchar(255)' },
          polled: { name: 'polled', type: 'int(11)' },
          lines: { name: 'lines', type: 'int(11)' },
          status: { name: 'status', type: 'int(11)' },
          html: { name: 'html', type: 'text' },
      },

}
var _client = _mysql.createConnection({ user: 'test', password: 'test' });
//var _db = _dbcrud.init(_client, 'rssem', model);


var _httpPort = 9009;
var _myHttpPath = 'http://srv01.flaregames.net:9009/';
var _app = _express();
_app.use(_express.bodyParser());

///////////////////////////////////////////////////////////////

function httpGet(url,cb) {
	console.log('requesting',url);
	
	var options = _url.parse(url);
	var req = _http.get(options, function(res) {
		res.setEncoding('utf8');
		
		pageData = '';
		
		res.on('data', function (chunk) {
			pageData+=chunk;
		});
		
		res.on('end',function() {
			return cb(pageData);
		});
	});

	req.on('error', function(e) {
		console.log('problem with request: ' + e.message);
		return cb(false);
	});

	req.end();
}

///////////////////////////////////////////////////////////////

function handleItems(items,cb) {
	var todo = items.length;

	return _processor.filter(items[0],cb);
	
	for(var i in items) {
        _processor.filter(items[i],function() {
			todo--;
			if(todo===0) {
				return cb();
			}
		});		
	}
}

///////////////////////////////////////////////////////////////

_app.get('/status/',function(req,res) {
	res.json({ok:1});
});

_app.get('/',function(req,res) {
	if (!req.query.url) {
		return res.send(500,'boom');
	}

	if (req.query.url.toLowerCase().substr(0,7) != 'http://') {
		return res.send(500,'not http://');
	}
	
	var url = req.query.url;
	return httpGet(url,function(html) {
		if (!html) {
			console.log('error pulling',url);
			return res.send(html);
		}
		
		return _parser.parseString(html,{},function(err,feed) {
			if (err) {
				res.send(500,'parse error for '+url);
			}

			if (feed.items.count === 0) {
				res.send(500,'no feed items found');
			}
			
			var xw = new _xmlwriter(true)
			var aw = new _atomwriter(xw);
			
			return handleItems(feed.items,function() {
				try {
				aw
					.startFeed(url)
					.writeLink(url, 'application/atom+xml', 'self')
					.writeTitle(feed.metadata.title);
			
				for (var i in feed.items) {
					var item=feed.items[i];
					aw
						.startEntry(item.guid)
						.writeTitle(item.title)
						.writeLink(item.guid)
						.writeContent(item.desc)
						.writeCategory(item.category)
						.endEntry();
				}
			
				aw.endFeed();
				res.type('application/atom+xml');
				return res.send(xw.toString());
				} 
				catch (e) {
					return res.send(500,'feed processing error '+url);
				}
			});
		});
	});
});

_app.listen(_httpPort);
console.log('ready',_httpPort);
