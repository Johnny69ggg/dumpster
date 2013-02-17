//feed.js
var xml2js = require('xml2js');
var _ = require('underscore');
var request = require('request');
var URL = require('url');

/**
All you need to do is send a feed URL that can be opened via fs
Options are optional, see xml2js for extensive list
And a callback of course

The returned formats will be structually the same, but you should still check the 'format' property
**/
function parseURL(feedURL, options, callback){
  if (typeof options == 'function' && !callback) {
    callback = options;
    options = {};
  }
  var defaults = {uri:feedURL, jar:false, proxy:false, followRedirect:true, timeout:1000*30};
  options = _.extend(defaults, options);
  //check that the protocall is either http or https
  var u = URL.parse(feedURL);
  if(u.protocol == 'http:' || u.protocol == 'https:'){
    //make sure to have a 30 second timeout
    var req = request(options, function (err, response, xml) {
      if(err || xml == null){
        if(err){
          callback(err,null);
        }else{
          callback('failed to retrive source', null);
        }
      }else{
        parseString(xml, options, callback);
      }
    });
  }else{
    callback({error:"Only http or https protocalls are accepted"}, null);
  }
}
module.exports.parseURL = parseURL;

function parseString(xml, options, callback){
  // we need to check that the input in not a null input
  if(xml.split('<').length >= 3) {
    var parser = new xml2js.Parser({trim:false, normalize:true, mergeAttrs:true});
    parser.addListener('end',function(jsonDOM){
      if(jsonDOM){
        //console.log(jsonDOM.rss.channel[0]);
        jsonDOM = normalize(jsonDOM);
        var err, output;
        if(isRSS(jsonDOM)){
          output = formatRSS(jsonDOM);
		  output.type = 'rss';
        }else{
          output = formatATOM(jsonDOM);
		  output.type = 'atom';
        }
        callback(null, output);
      }else{
        callback("failed to parse xml", null);
      }
    });
    parser.addListener("error", function(err){
      callback(err, null);
    });
    parser.parseString(xml);
  }else{
    callback('malformed xml', null);
  }
}
module.exports.parseString = parseString;

//detects if RSS, otherwise assume atom
function isRSS(json){
	return (json.channel != null);
}

// normalizes input to make feed burner work
function normalize(json) {
  if (json.rss) {
    return json.rss;
  }
  return json;
}

//xml2js will return commented material in a # tag which can be a pain
//this will remove the # tag and set its child text in it's place
//ment to work on a feed item, so will iterate over json's and check
function flattenComments(json){
	for(key in json){
		if(json[key]['#']){
			json[key] = json[key]['#'];
		}
	}
	return json;
}

//formats the RSS feed to the needed outpu
//also parses FeedBurner
function formatRSS(json){
	var output = {'type':'rss', metadata:{}, items:[]};
	//Start with the metadata for the feed
	var metadata = {};
	var channel = json.channel;

  if (_.isArray(json.channel)) {
    channel = json.channel[0];
  }

	metadata.title = '';
	if(channel.title){
		metadata.title = channel.title[0];
	}	
	if(channel.description){
		metadata.desc = channel.description[0];
	}
	if(channel.link){
		metadata.url = channel.link[0];
	}
	if(channel.lastBuildDate){
		metadata.lastBuildDate = channel.lastBuildDate;
	}
	if(channel.pubDate){
		metadata.update = channel.pubDate;
	}
	if(channel.ttl){
		metadata.ttl = channel.ttl;
	}
	output.metadata = metadata;
	//ok, now lets get into the meat of the feed
	//just double check that it exists
	if(channel.item){
		if(!_.isArray(channel.item)){
			channel.item = [channel.item];
		}
		_.each(channel.item, function(val, index){
			val = flattenComments(val);
			var obj = {};
			obj.title = val.title[0];
			if (typeof obj.title !== 'string') {
				obj.title = '';
			}
			
			obj.desc = val.description[0];
			if (typeof obj.desc !== 'string') {
				obj.desc = '';
			}

			if (val['content:encoded'] && val['content:encoded'][0].length > obj.desc.length) {
				obj.desc = val['content:encoded'][0];
			}

			obj.category = '';
			
			obj.link = val.link[0];
			if(val.category){
				obj.category = val.category.join();
			}
			//since we are going to format the date, we want to make sure it exists
			if(val.pubDate){
				//lets try basis js date parsing for now
				obj.date = Date.parse(val.pubDate);
			}
			
			obj.guid = obj.link;
			
			//now lets handel the GUID
			if(val.guid){
				if (val.guid[0] && typeof val.guid[0] === 'string') {
					obj.guid = val.guid[0];
				} else {
				//xml2js parses this kina odd...
				var link = val.guid;
//					var param = val.guid['@'];
				var isPermaLink = true;
				//if(param){
				//	isPermaLink = param.isPermaLink;
				//}
				obj.guid = link[0]['_']; 
				}
			}
			
			//now push the obj onto the stack
			output.items.push(obj);
		});	
	}
	return output;
}

//formats the ATOM feed to the needed output
//yes, this is a shamless copy-pasta of the RSS code (its all the same structure!)
function formatATOM(json){
	var output = {'type':'atom', metadata:{}, items:[]};
	//Start with the metadata for the feed
	var metadata = {};
	var channel = json;
	
	metadata.title = '';
	if(channel.title){
		metadata.title = channel.title;
	}	
	if(channel.subtitle){
		metadata.desc = channel.subtitle;
	}
	metadata.url = '';
	if(channel.link){
		metadata.url = channel.link;
	}
	if(channel.id){
		metadata.id = channel.id;
	}
	if(channel.update){
		metadata.update = channel.update;
	}
	if(channel.author){
		metadata.author = channel.author;
	}
	
	output.metadata = metadata;
	//just double check that it exists and that it is an array
	if(channel.entry){
		if(!_.isArray(channel.entry)){
			channel.entry = [channel.entry];
		}
		_.each(channel.entry, function(val, index){
			val = flattenComments(val);
			var obj = {};
			obj.id = val.id;
			if(!val.title){
				console.log(json);
			}
			obj.title = val.title;
			if(val.content){
				obj.desc = val.content;
			}else if(val.summary){
				obj.desc = val.summary;
			}
			
			if (typeof obj.desc !== 'string') {
				obj.desc = '';
			}
			
			if (val['content:encoded'] && val['content:encoded'][0].length > obj.desc.length) {
				obj.desc = val['content:encoded'][0];
			}

			var categories = [];
			//just grab the category text
			if(val.category){
				if(_.isArray(val.category)){
					_.each(val.category, function(val, i){
						categories.push(val['term']);
					});
				}else{
					categories.push(val.category);
				}
			}
			obj.category = categories.join(' ');
			var link = '';
			//just get the alternate link
			if(val.link){
				if(_.isArray(val.link)){
					_.each(val.link, function(val, i){
						if(val.rel == 'alternate'){
							link = val.href;
						}
					});
				}else{
					link = val.link.href;
				}
			}
			obj.link = link;
			obj.guid = link;
			//since we are going to format the date, we want to make sure it exists
			if(val.published){
				//lets try basis js date parsing for now
				obj.date = Date.parse(val.published);
			}
			if(val.updated){
				//lets try basis js date parsing for now
				obj.updated = Date.parse(val.updated);
			}
			//now push the obj onto the stack
			output.items.push(obj);
		});	
	}
	return output;
}

