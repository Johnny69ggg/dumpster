var _jscrape = require('jscrape');


function normaliseHtml(html) {
	html = html.replace(/&lt;/g,'<');
	html = html.replace(/&gt;/g,'>');

	html = html.replace(/\s/g,' ');
	html = html.replace(/\s{2,}/g,' ');
	html = html.replace(/<\s/g,'<');
	html = html.replace(/>\s/g,'>');
	
	return html;
}


function findOpeningTag(txt,start) {
		var tagOpen = start-1, skip=0;
		
		while(1) {
			while (tagOpen >= 0) {
				if (txt.substr(tagOpen,1) === '<') {
					if (skip > 0) {
						skip--;
					} else {
						break;
					}
				}
				tagOpen--;
			}
			if (tagOpen < 0) {
				return false;
			}
//			console.log('tagOpen',tagOpen);
			
			var fullTag = txt.substr(tagOpen,start-tagOpen);
//			console.log('fullTag',fullTag);
			
			if (fullTag.substr(1,1) === '/') {
				skip=1;
				tagOpen--;
			} else {
				break;
			}
		}

		var x1=fullTag.indexOf('>');
		var x2=fullTag.indexOf(' ');
		var x;
		
		if (x1 !== -1) {
			x=x1;
		}
		if (x2 !== -1 && x2 < x1) {
			x=x2;
		}
		
		return fullTag.substr(1,x-1);
}


function findClosingTag(txt,tagName,start) {
	var openTag = '<'+tagName, 
		closeTag = '</'+tagName+'>', 
		open, 
		close,
		current = start;

	while (1) {
		close = -1;
		open = txt.indexOf(openTag,current);
		if (open >= 0) {
			close = txt.indexOf(closeTag,open);
		} else {
			close = txt.indexOf(closeTag,current);
		}
		
		if (close !== -1) {
			if (open === -1) {
				return close;
			}
			
			if (close > open) {
				current = close + 1;
			} 
		} else {
			return false;
		}
		
	}
}


exports.filter = function(item,cb) {
	console.log('processing',item.link);
	
	return _jscrape(item.link, function (err, $, res, body) {
		if (err) {
			console.log('http get error',item.link);
			return cb(false);
		}
		
		body = normaliseHtml(body);
		var desc=normaliseHtml(item.desc);
		
		var textStart = body.indexOf(desc.substr(0,128));
		if (textStart === -1) {
			console.log('text start not found');
			return cb(false);
		}
		
		var tagName = findOpeningTag(body,textStart); 
		if (tagName === false) {
			console.log('cant find openingtag');
			return cb(false);
		}

		var textEnd = findClosingTag(body,tagName,textStart + desc.length);
		if (textEnd === false) {
			console.log('could not find text end');
			return cb(false);
		}
		
		var newText = body.substr(body, textEnd - textStart);
		
		if (newText.length > item.desc.length) {
			console.log('longer text found',item.desc.length,'vs',newText.length);
			item.desc = newText;
		}
		
		return cb(true);
	});
}


var lala = 'start<b>1</b>2<b>1</b>2<b>1</b>2<b>1</b>2<b>1</b>2<b>1</b>2</b>ende';
var x=findClosingTag(lala,'b',2);
console.log(lala.substr(x));