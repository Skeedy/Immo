<script>
//vars
var _PROTOCOL = '<?php echo _PROTOCOL; ?>';
var _SERVER_NAME = '<?php echo $_SERVER['HTTP_HOST']; ?>';
var _ROOT = '<?php echo _ROOT; ?>';
var _ROOT_ADMIN = '<?php echo _ROOT_ADMIN; ?>';
var _DIR_THUMBS = '<?php echo _DIR_THUMBS; ?>';
var mainTop;
	
//maxlength
(function(a){a.fn.maxlength=function(b){var c=a(this);return c.each(function(){b=a.extend({},{counterContainer:!1,text:"%left caractères restant"},b);var c=a(this),d={options:b,field:c,counter:a('<div class="maxlength"></div>'),maxLength:parseInt(c.attr("maxlength"),10),lastLength:null,updateCounter:function(){var b=this.field.val().length,c=this.options.text.replace(/\B%(length|maxlength|left)\b/g,a.proxy(function(a,c){return"length"==c?b:"maxlength"==c?this.maxLength:this.maxLength-b},this));this.counter.html(c),b!=this.lastLength&&this.updateLength(b)},updateLength:function(a){this.field.trigger("update.maxlength",[this.field,this.lastLength,a,this.maxLength,this.maxLength-a]),this.lastLength=a}};d.maxLength&&(d.field.data("maxlength",d).bind({"keyup change":function(){a(this).data("maxlength").updateCounter()},"cut paste drop":function(){setTimeout(a.proxy(function(){a(this).data("maxlength").updateCounter()},this),1)}}),b.counterContainer?b.counterContainer.append(d.counter):d.field.after(d.counter),d.updateCounter())}),c}})(jQuery);	
	
Array.prototype.inArray = function(p_val) {
	var l = this.length;
	for(var i = 0; i < l; i++) {
		if(this[i] == p_val)
			return true;
	}
	return false;
}

/*! jQuery ellipsis - v1.1.1 - 2014-02-23
* https://github.com/STAR-ZERO/jquery-ellipsis
* Copyright (c) 2014 Kenji Abe; Licensed MIT */
!function(a){a.fn.ellipsis=function(b){var c={row:1,onlyFullWords:!1,"char":"...",callback:function(){},position:"tail"};return b=a.extend(c,b),this.each(function(){var c=a(this),d=c.text(),e=d,f=e.length,g=c.height();c.text("a");var h=parseFloat(c.css("lineHeight"),10),i=c.height(),j=h>i?h-i:0,k=j*(b.row-1)+i*b.row;if(k>=g)return c.text(d),void b.callback.call(this);var l=1,m=0,n=d.length;if("tail"===b.position){for(;n>l;)m=Math.ceil((l+n)/2),c.text(d.slice(0,m)+b["char"]),c.height()<=k?l=m:n=m-1;d=d.slice(0,l),b.onlyFullWords&&(d=d.replace(/[\s,]*[,\u2019'1-9A-Za-z\u00C0-\u017F]+[\s,]*$/,"")),d+=b["char"]}else if("middle"===b.position){for(var o=0;n>l;)m=Math.ceil((l+n)/2),o=Math.max(f-m,0),c.text(e.slice(0,Math.floor((f-o)/2))+b["char"]+e.slice(Math.floor((f+o)/2),f)),c.height()<=k?l=m:n=m-1;o=Math.max(f-l,0);var p=e.slice(0,Math.floor((f-o)/2)),q=e.slice(Math.floor((f+o)/2),f);b.onlyFullWords&&(p=p.replace(/[\s,]*[,\u2019'1-9A-Za-z\u00C0-\u017F]+[\s,]*$/,"")),d=p+b["char"]+q}c.text(d),b.callback.call(this)}),this}}(jQuery);


!function(e){e.fn.serializeObject=function(){var i=this,t={},a={},n={validate:/^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,key:/[a-zA-Z0-9_]+|(?=\[\])/g,push:/^$/,fixed:/^\d+$/,named:/^[a-zA-Z0-9_]+$/};return this.build=function(e,i,t){return e[i]=t,e},this.push_counter=function(e){return void 0===a[e]&&(a[e]=0),a[e]++},e.each(e(this).serializeArray(),function(){if(n.validate.test(this.name)){for(var a,u=this.name.match(n.key),h=this.value,r=this.name;void 0!==(a=u.pop());)r=r.replace(new RegExp("\\["+a+"\\]$"),""),a.match(n.push)?h=i.build([],i.push_counter(r),h):a.match(n.fixed)?h=i.build([],a,h):a.match(n.named)&&(h=i.build({},a,h));t=e.extend(!0,t,h)}}),t}}(jQuery);


function parseQueryString(query){
	if(query == '')
		return {};
	var setValue = function(root, path, value){
		if(path.length > 1){
			var dir = path.shift();
			if( typeof root[dir] == 'undefined' ){
				root[dir] = path[0] == '' ? [] : {};
			}
			arguments.callee(root[dir], path, value);
		}else{
			if( root instanceof Array ){
				root.push(value);
			}else{
				root[path] = value;
			}
		}
	};
	var nvp = query.split('&');
	var data = {};
	for( var i = 0 ; i < nvp.length ; i++ ){
	var pair = nvp[i].split('=');
		var name = decodeURIComponent(pair[0]);
		var value = decodeURIComponent(pair[1]);
		var path = name.match(/(^[^\[]+)(\[.*\]$)?/);
		var first = path[1];
		if(path[2]){
			//case of 'array[level1]' || 'array[level1][level2]'
			path = path[2].match(/(?=\[(.*)\]$)/)[1].split('][')
		}else{
			//case of 'name'
			path = [];
		}
		path.unshift(first);
		setValue(data, path, value);
	}
	return data;
}


var defaultDiacriticsRemovalap = [
    {'base':'A', 'letters':'\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'},
    {'base':'AA','letters':'\uA732'},
    {'base':'AE','letters':'\u00C6\u01FC\u01E2'},
    {'base':'AO','letters':'\uA734'},
    {'base':'AU','letters':'\uA736'},
    {'base':'AV','letters':'\uA738\uA73A'},
    {'base':'AY','letters':'\uA73C'},
    {'base':'B', 'letters':'\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'},
    {'base':'C', 'letters':'\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'},
    {'base':'D', 'letters':'\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779'},
    {'base':'DZ','letters':'\u01F1\u01C4'},
    {'base':'Dz','letters':'\u01F2\u01C5'},
    {'base':'E', 'letters':'\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'},
    {'base':'F', 'letters':'\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'},
    {'base':'G', 'letters':'\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'},
    {'base':'H', 'letters':'\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'},
    {'base':'I', 'letters':'\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'},
    {'base':'J', 'letters':'\u004A\u24BF\uFF2A\u0134\u0248'},
    {'base':'K', 'letters':'\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'},
    {'base':'L', 'letters':'\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'},
    {'base':'LJ','letters':'\u01C7'},
    {'base':'Lj','letters':'\u01C8'},
    {'base':'M', 'letters':'\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'},
    {'base':'N', 'letters':'\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'},
    {'base':'NJ','letters':'\u01CA'},
    {'base':'Nj','letters':'\u01CB'},
    {'base':'O', 'letters':'\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'},
    {'base':'OI','letters':'\u01A2'},
    {'base':'OO','letters':'\uA74E'},
    {'base':'OU','letters':'\u0222'},
    {'base':'OE','letters':'\u008C\u0152'},
    {'base':'oe','letters':'\u009C\u0153'},
    {'base':'P', 'letters':'\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'},
    {'base':'Q', 'letters':'\u0051\u24C6\uFF31\uA756\uA758\u024A'},
    {'base':'R', 'letters':'\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'},
    {'base':'S', 'letters':'\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'},
    {'base':'T', 'letters':'\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'},
    {'base':'TZ','letters':'\uA728'},
    {'base':'U', 'letters':'\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'},
    {'base':'V', 'letters':'\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'},
    {'base':'VY','letters':'\uA760'},
    {'base':'W', 'letters':'\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'},
    {'base':'X', 'letters':'\u0058\u24CD\uFF38\u1E8A\u1E8C'},
    {'base':'Y', 'letters':'\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'},
    {'base':'Z', 'letters':'\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'},
    {'base':'a', 'letters':'\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'},
    {'base':'aa','letters':'\uA733'},
    {'base':'ae','letters':'\u00E6\u01FD\u01E3'},
    {'base':'ao','letters':'\uA735'},
    {'base':'au','letters':'\uA737'},
    {'base':'av','letters':'\uA739\uA73B'},
    {'base':'ay','letters':'\uA73D'},
    {'base':'b', 'letters':'\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'},
    {'base':'c', 'letters':'\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'},
    {'base':'d', 'letters':'\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'},
    {'base':'dz','letters':'\u01F3\u01C6'},
    {'base':'e', 'letters':'\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'},
    {'base':'f', 'letters':'\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'},
    {'base':'g', 'letters':'\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'},
    {'base':'h', 'letters':'\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'},
    {'base':'hv','letters':'\u0195'},
    {'base':'i', 'letters':'\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'},
    {'base':'j', 'letters':'\u006A\u24D9\uFF4A\u0135\u01F0\u0249'},
    {'base':'k', 'letters':'\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'},
    {'base':'l', 'letters':'\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'},
    {'base':'lj','letters':'\u01C9'},
    {'base':'m', 'letters':'\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'},
    {'base':'n', 'letters':'\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'},
    {'base':'nj','letters':'\u01CC'},
    {'base':'o', 'letters':'\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'},
    {'base':'oi','letters':'\u01A3'},
    {'base':'ou','letters':'\u0223'},
    {'base':'oo','letters':'\uA74F'},
    {'base':'p','letters':'\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'},
    {'base':'q','letters':'\u0071\u24E0\uFF51\u024B\uA757\uA759'},
    {'base':'r','letters':'\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'},
    {'base':'s','letters':'\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'},
    {'base':'t','letters':'\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'},
    {'base':'tz','letters':'\uA729'},
    {'base':'u','letters': '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'},
    {'base':'v','letters':'\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'},
    {'base':'vy','letters':'\uA761'},
    {'base':'w','letters':'\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'},
    {'base':'x','letters':'\u0078\u24E7\uFF58\u1E8B\u1E8D'},
    {'base':'y','letters':'\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'},
    {'base':'z','letters':'\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'}
];
var diacriticsMap = {};
for (var i=0; i < defaultDiacriticsRemovalap.length; i++){
    var letters = defaultDiacriticsRemovalap[i].letters.split("");
    for (var j=0; j < letters.length ; j++){
        diacriticsMap[letters[j]] = defaultDiacriticsRemovalap[i].base;
    }
}
function removeDiacritics(str) {
    return str.replace(/[^\u0000-\u007E]/g, function(a){ 
       return diacriticsMap[a] || a; 
    });
}

function stripRoot(str) {
	return str.replace("<?php echo _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT; ?>", "");
}

function stripRootMedia(str) {
	return str.replace("<?php echo _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT._DIR_MEDIA; ?>", "");
}

function cleanUrl(str, removeSlashes = false) {
	str = removeDiacritics(str);
    str = str.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z\/0-9-]/g, '').replace(/^-*(.+?)-*$/, '$1');
    if(removeSlashes)
		str = str.replace(/\//g, '');
    return str;
}


function insertImage(input, div, multi, name_file) {
	if(typeof multi == 'undefined')
		multi = true;
	if(typeof name_file == 'undefined')
		name_file = 'images[]';
	var file = stripRootMedia(input.val());
	var str = '<div class="thumb" style="background-image:url(' + _ROOT + _DIR_THUMBS + file + ');"><a href="' + input.val() + '" class="fancybox_img"></a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><input type="hidden" name="' + name_file + '" value="' + file + '"></div>';
	if(multi)
		div.append(str);
	else
		div.html(str);
	input.val('');
}

function insertFile(input, div, multi, name_file) {
	if(typeof multi == 'undefined')
		multi = true;
	if(typeof name_file == 'undefined')
		name_file = 'images[]';
	var file = stripRootMedia(input.val());
	var str = '<div class="file"><a href="' + input.val() + '" target="_blank">' + file + '</a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><input type="hidden" name="' + name_file + '" value="' + file + '"></div>';
	if(multi)
		div.append(str);
	else
		div.html(str);
	input.val('');
}

function insertVideo(input, div, multi, name_type, name_file) {
	if(typeof multi == 'undefined')
		multi = true;
	if(typeof name_type == 'undefined')
		name_type = 'images_type[]';
	if(typeof name_file == 'undefined')
		name_file = 'images_fichier[]';
	var file = stripRootMedia(input.val());
	insertItemList('<?php echo _ROOT._DIR_MEDIA; ?>' + file, '', '_blank', '<?php echo _ROOT.'show_video_thumb.php?filename='._DIR_MEDIA; ?>' + file, 'video', file, div, multi, name_type, name_file);
	input.val('');
}

function insertVideoEmbed(input, div, multi, name_file) {
	if(typeof multi == 'undefined')
		multi = true;
	if(typeof name_type == 'undefined')
		name_type = 'images_type[]';
	if(typeof name_file == 'undefined')
		name_file = 'images_fichier[]';
	var url = input.val();
	var res;
	if(res = url.match(/(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i)) {
		var thumb_url = 'http://img.youtube.com/vi/' + res[3] + '/0.jpg';
	}
	else if(res = url.match(/dailymotion.com\/video\/(.*)\/?(.*)/)) {
		var thumb_url = 'http://www.dailymotion.com/thumbnail/video/' + res[1];
	}
	else if(res = url.match(/(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/)) {
		var thumb_url;
		$.getJSON('http://vimeo.com/api/v2/video/' + res[1] + '.json?callback=?').done(function(data) {
			thumb_url = data[0].thumbnail_large;
		});
	}
	else
		return false;
		
	var str = '<div class="thumb" style="background-image:url(' + thumb_url + ');"><a href="' + input.val() + '" class="fancybox_media"></a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><input type="hidden" name="' + name_file + '" value="' + input.val() + '"></div>';
	if(multi)
		div.append(str);
	else
		div.html(str);
	input.val('');
}

function initTinyMCE() {
	/*$(".editor:not('.editor.tinymce')").each(function(i, el) {
		var container = $(el).is('[data-container]') ? $(el).data('container') : 'body';
		//alert(container);*/
	tinymce.init({
		selector: ".editor:not('.editor.tinymce')",
		//target: el,
		//ui_container: '#test',
		theme: "modern",
		language : "fr_FR",
		relative_urls : false,
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code autoresize"
		],
		image_dimensions: false,
		image_advtab: true,
		//autoresize_bottom_margin: 50,
		element_format : 'html',
		verify_html : false,
		content_css : ["<?php echo _ROOT; ?>lib/bootstrap/css/bootstrap.min.css?" + new Date().getTime(),"<?php echo _ROOT; ?>css/style.css?" + new Date().getTime()],
		document_base_url: "<?php echo _PROTOCOL.$_SERVER['HTTP_HOST']; ?>",
		external_filemanager_path:"<?php echo _ROOT_ADMIN; ?>lib/filemanager/filemanager/",
		filemanager_title:"Responsive Filemanager" ,
		external_plugins: { "filemanager" : "<?php echo _ROOT_ADMIN; ?>lib/filemanager/filemanager/plugin.min.js"},
		style_formats: [
			{ title: 'Majuscules', inline: 'span', classes: 'text-uppercase' },
			{ title: 'Vidéo responsive 16/9', block: 'div', classes: 'embed-responsive embed-responsive-16by9' },
			{ title: 'Vidéo responsive 4/3', block: 'div', classes: 'embed-responsive embed-responsive-4by3' }
		],
		style_formats_merge: true,
		formats: {
			alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-left' },
			aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-center' },
			alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-right' },
			alignjustify: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-justify' }
		},
		body_class: 'editor'
	});
//});
}


function initialize_map(el) {
	/*if(!$(el).hasClass('map_constructor'))
		return false;
	var gmapdefault = new google.maps.LatLng(46.2157467,2.2088258);
	var mapOptions = {
		zoom: 4,
		center: gmapdefault,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	}
	var map = new google.maps.Map(el, mapOptions);

	var geocoder = new google.maps.Geocoder();

	var marker = new google.maps.Marker({
		map: null,
		draggable: true,
		icon: '<?php echo _ROOT._DIR_IMG; ?>pointer.png',
		position: new google.maps.LatLng(-44.895776,179.407997),
		position_changed: function() {
			$('#' + $(el).data('prefix') + 'latitude').val(this.position.lat());
			$('#' + $(el).data('prefix') + 'longitude').val(this.position.lng());
		}
	});
	if($('#' + $(el).data('prefix') + 'latitude').val() != '' && $('#' + $(el).data('prefix') + 'longitude').val() != '') {
		map.setCenter(new google.maps.LatLng($('#' + $(el).data('prefix') + 'latitude').val(), $('#' + $(el).data('prefix') + 'longitude').val()));
		map.setZoom(15);
		marker.setPosition(new google.maps.LatLng($('#' + $(el).data('prefix') + 'latitude').val(), $('#' + $(el).data('prefix') + 'longitude').val()));
		if($('#' + $(el).data('prefix') + 'pointer').val() == 1)
			marker.setMap(map);
	}
	$('#' + $(el).data('prefix') + 'btn_localize').click(function() {
		geocoder.geocode({'address': $('#' + $(el).data('prefix') + 'adresse').val()},function(results, status){
			if(status == google.maps.GeocoderStatus.OK){
				marker.setPosition(results[0].geometry.location);
				if($('#' + $(el).data('prefix') + 'pointer').val() == 1)
					marker.setMap(map);
				map.setCenter(results[0].geometry.location);
				map.setZoom(15);
				$('#' + $(el).data('prefix') + 'latitude').val(results[0].geometry.location.lat());
				$('#' + $(el).data('prefix') + 'longitude').val(results[0].geometry.location.lng());
			}
			else {
				alert('Google maps n\'a pas réussi à localiser cette adresse.');
				$('#' + $(el).data('prefix') + 'latitude').val('');
				$('#' + $(el).data('prefix') + 'longitude').val('');
			}
		});
	});
	$('#' + $(el).data('prefix') + 'pointer').change(function() {
		marker.setMap($(this).val() == 1 ? map : null);
	});
	$(el).data('map', map);*/
}


function parseBlocksToParse() {
	$('.block_to_parse[data-type]').each(function(i, el) {
		var val;
		try {
			val = JSON.parse($('input[type="hidden"]:first', el).val());
		}
		catch(e) {
			val = {};
		}
		var txt = '';
		var placeholder = '';
		
		if($(el).data('type') == 'text') {
			if(val.hasOwnProperty('<?php echo _LANG_DEFAULT; ?>') && val['<?php echo _LANG_DEFAULT; ?>'] != '')
				txt = $('<div>' + val['<?php echo _LANG_DEFAULT; ?>'].replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, ' ').replace(/<br\s*[\/]?>/gi, ' ') + '</div>').text().substring(0, 1000);
			else
				placeholder = 'Cliquez pour ajouter du contenu';
		}
		else if($(el).data('type') == 'texthidden') {
			if(val.hasOwnProperty('text') && val['text'].hasOwnProperty('<?php echo _LANG_DEFAULT; ?>') && val['text']['<?php echo _LANG_DEFAULT; ?>'] != '')
				txt = $('<div>' + val['text']['<?php echo _LANG_DEFAULT; ?>'].replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, ' ').replace(/<br\s*[\/]?>/gi, ' ') + '</div>').text().substring(0, 1000);
			else
				placeholder = 'Cliquez pour ajouter du contenu';
		}
		else if($(el).data('type') == 'diaporama_element' || $(el).data('type') == 'gallery_element') {
			if(val.hasOwnProperty('image') && val['image'] != '')
				txt = '<img src="' + _ROOT + _DIR_THUMBS + val['image'] + '">';
			else
				placeholder = 'Aucune image';
		}
		else if($(el).data('type') == 'map') {
			if(val.hasOwnProperty('adresse') && val['adresse'] != '')
				txt = $('<div>' + val['adresse'].replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, ' ').replace(/<br\s*[\/]?>/gi, ' ') + '</div>').text().substring(0, 1000);
			else
				placeholder = 'Cliquez pour configurer la carte';
		}
		
		if($('.val', el).get().length == 0)
			$(el).append('<div class="val"></div>');
		if(placeholder == '') {
			$('.val.placeholder', el).removeClass('placeholder');
			$('.val', el).html(txt);
		}
		else {
			$('.val:not(.placeholder)', el).addClass('placeholder');
			$('.val', el).html(placeholder);
		}
		$(el).removeClass('block_to_parse').addClass('block_parsed');
		
		if($(el).data('type') == 'text' || $(el).data('type') == 'texthidden') {
			$('.val', el).ellipsis({
				row: 2,
				onlyFullWords: true
			});
		}
	});
}


function populateFormFromObject(formWrapper, obj, keys) {
	if(keys === undefined)
		keys = '';
	for(var property in obj) {
		if(obj.hasOwnProperty(property)) {
			if(property !== null && typeof obj[property] === 'object')
				populateFormFromObject(formWrapper, obj[property], keys == '' ? property : keys + '[' + property + ']');
			else {
				var $field = $(':input[name="' + (keys == '' ? property : keys + '[' + property + ']') +'"]', formWrapper);
				if($field.length != 0) {
					if($field.is(':checkbox') || $field.is(':radio'))
						$field.prop('checked', true);
					else
						$field.val(obj[property]);
					if($field.hasClass('editor'))
						tinymce.get($field.attr('id')).load();
				}
			}
		}
	}
}


function initSortable() {
	$('.list-sortable').sortable({ scroll: false, tolerance: "pointer" });
	
	$('.table-sortable tbody').sortable({
		handle: '.glyphicon-resize-vertical',
		axis: "y",
		items: $(this).is('[data-itemes]') ? $(this).data('itemes') : "> *",
		cancel: ".nosort",
		forcePlaceholderSize: true,
		placeholder: 'placeholder',
		helper: function fixWidthHelper(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		}
	});
	
	$('.list-sortable-handle-dissmissable:not(.all-directions)').sortable({
		handle: '.glyphicon-resize-vertical',
		axis: "y",
		items: "> .dissmissable-block",
		cancel: ".nosort",
		placeholder: 'dissmissable-block-simple-placeholder',
		forcePlaceholderSize: true,
		update: function(event, ui) {
			if(typeof preview === 'function')
				preview();
		}
	});
	
	$('.list-sortable-handle-dissmissable.all-directions').sortable({
		handle: '.glyphicon-move',
		items: "> .dissmissable-block",
		cancel: ".nosort",
		tolerance: "pointer",
		placeholder: 'dissmissable-block-thumb-placeholder',
		forcePlaceholderSize: true
	});
	
	$('.list-sortable-handle-dissmissable-interchangeable').sortable({
		handle: 'button.sort',
		items: "> .dissmissable-block",
		cancel: ".nosort",
		connectWith: '.list-sortable-handle-dissmissable-interchangeable',
		tolerance: "pointer",
		placeholder: 'dissmissable-block-placeholder',
		forcePlaceholderSize: true,
		update: function(event, ui) {
			var new_prefix = $('.addsection[data-name]:last', $(ui.item.parents('.list-sortable-handle-dissmissable-interchangeable').get(0)).parent().get(0)).data('name');
			if($('.addsection[data-name]:last', ui.item[0]).get().length == 0)
				var old_prefix = $(':input[name]:first', ui.item[0]).attr('name').replace(/^(.*)\[iteration\d+\].*?$/, "$1");
			else
				var old_prefix = $('.addsection[data-name]:last', ui.item[0]).attr('data-name').replace(/^(.*)\[iteration\d+\].*?$/, "$1");
			//alert(old_prefix+' -> '+new_prefix);
			$(':input[name]', ui.item[0]).each(function(i, el) {
				$(el).attr('name', $(el).attr('name').replace(old_prefix, new_prefix));
			});
			$('.addsection[data-name]', ui.item).each(function(i, el) {
				$(el).attr('data-name', $(el).attr('data-name').replace(old_prefix, new_prefix));
			});
		} 
	});
}


function toDataURL(src, callback, outputFormat) {
	var img = new Image();
	img.crossOrigin = 'Anonymous';
	img.onload = function() {
		var canvas = document.createElement('CANVAS');
		var ctx = canvas.getContext('2d');
		var dataURL;
		canvas.height = this.naturalHeight;
		canvas.width = this.naturalWidth;
		ctx.drawImage(this, 0, 0);
		dataURL = canvas.toDataURL(outputFormat);
		callback(dataURL);
	};
	img.src = src;
	if (img.complete || img.complete === undefined) {
		img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
		img.src = src;
	}
}

$(document).ready(function(){

    if ($('#frm_annonce_type option:selected').val() =='2'){
        $("div.myDiv").show();
        
    }
    else {
    	$("div.myDiv").hide();
    }
    $('#frm_annonce_type').on('change', function(){
    	var demovalue = $(this).val(); 
        $("div.myDiv").hide();
        $("#show"+demovalue).show();
    });
});


$(function() {

	$('input, textarea').placeholder();
	
	$('input[maxlength], textarea[maxlength]').maxlength();
	
	$('#mobile_menu select').change(function() {
		location.href = $(this).val();
	});

	$('select').each(function(i, el) {
		if($(el).val() == '')
			$(el).addClass('placeholder');
		else
			$(el).removeClass('placeholder');
	});
	$('body').on('change', 'select', function() {
		if($(this).val() == '')
			$(this).addClass('placeholder');
		else
			$(this).removeClass('placeholder');
	});
	
	$('body').on('focus', '.nofocus', function() {
		$(this).blur();
	});
	
	
	$('body').on('click', '.list-sortable .close', function() {
		$(this).parents('.thumb, .file').remove();
	});

	$('a.delete-confirm').click(function() {
		return confirm($(this).data('delete-msg'));
	});
	
	$('body').on('click', '.btn-onoff', function() {
		var $self = $(this);
		var $el = $self.next('input');
		if($el.val() == 0) {
			$el.val(1);
			$self.button('on').removeClass('btn-danger').addClass('btn-success');
		}
		else {
			$el.val(0);
			$self.button('off').removeClass('btn-success').addClass('btn-danger');
		}
		$el.change();
	});

	$('.btn-onoff').each(function() {
		var $self = $(this);
		var $el = $self.next('input');
		if($el.val() == 1)
			$self.button('on').removeClass('btn-danger').addClass('btn-success');
		else
			$self.button('off').removeClass('btn-success').addClass('btn-danger');
	});

	$(".fancybox").fancybox({
		fitToView	: false,
		width		: '90%',
		height		: '90%',
		autoSize	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers: {
			overlay: {
				locked: false
			}
		}
	});
	$(".fancybox_img").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers: {
			overlay: {
				locked: false
			}
		}
	});
	$('.fancybox_media').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
			media : {},
			overlay: {
				locked: false
			}
		}
	});

	$('body').on('click', '.form-group.dismissible .close', function() {
		$($(this).parents('.form-group').get(0)).remove();
	});

	$('.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "fr",
		autoclose: true,
		todayHighlight: true
	});

	$('.filtertext').on('change keyup blur', function() {
		var term = removeDiacritics($(this).val().toLowerCase()).split(' ');
		if(term.length == 0)
			$($(this).data('target') + ' tr').show();
		else {
			$($(this).data('target') + ' tr').each(function(i) {
				var found = true;
				if($(this).data('search')) {
					var i = 0;
					while(found && i < term.length) {
						if(!$(this).data('search') || $(this).data('search').toLowerCase().indexOf(term[i]) == -1)
							found = false;
						i++
					}
				}
				if(found)
					$(this).show();
				else
					$(this).hide();
			});
		}
	});

	

	$('.agenda').each(function(i, el) {
		var hotel = $(el).is('[data-hotel]') ? $(el).data('hotel') : '';
		var chambre = $(el).is('[data-chambre]') ? $(el).data('chambre') : '';
		$.ajax({
			type: "POST",
			url: "<?php echo _ROOT_ADMIN; ?>agenda",
			data: { mois: '', hotel: hotel, chambre: chambre }
		}).done(function(data) {
			$('.agenda_mois', el).html(data);
			$('.haspopover', el).popover({
				animation: false,
				html: true,
				trigger: 'hover'
			});
			if(hotel == '' && chambre == '') {
				var vals = $('textarea', el).val() != '' ? $('textarea', el).val().split(';') : new Array();
				for(i = 0; i < vals.length; i++)
					$('.cell.active[data-day="' + vals[i] + '"]', el).removeClass('free').addClass('nonfree');
			}
		});
	});
	
	$('body').on('click', '.agenda .toolzone-toggle', function(e) {
		e.preventDefault();
		$(this).next('.toolzone').toggle('blind', 200);
	});
	
	$('body').on('click', '.agenda .control', function() {
		var $self = $(this);
		var agenda = $self.parents('.agenda')[0];
		var hotel = $(agenda).is('[data-hotel]') ? $(agenda).data('hotel') : '';
		var chambre = $(agenda).is('[data-chambre]') ? $(agenda).data('chambre') : '';
		$.ajax({
			type: "POST",
			url: "<?php echo _ROOT_ADMIN; ?>agenda",
			data: { mois: $self.data('date'), hotel: hotel, chambre: chambre }
		}).done(function(data) {
			$('.agenda_mois', agenda).html(data);
			$('.haspopover', agenda).popover({
				animation: false,
				html: true,
				trigger: 'hover'
			});
			if(hotel == '' && chambre == '') {
				var vals = $('textarea', agenda).val() != '' ? $('textarea', agenda).val().split(';') : new Array();
				for(i = 0; i < vals.length; i++)
					$('.cell.active[data-day="' + vals[i] + '"]', agenda).removeClass('free').addClass('nonfree');
			}
		});
	});
	
	$('body').on('click', '.agenda .cell.active', function() {
		var $self = $(this);
		var agenda = $self.parents('.agenda')[0];
		var hotel = $(agenda).is('[data-hotel]') ? $(agenda).data('hotel') : '';
		var chambre = $(agenda).is('[data-chambre]') ? $(agenda).data('chambre') : '';
		if(hotel == '' && chambre == '') {
			var vals = $('textarea', agenda).val() != '' ? $('textarea', agenda).val().split(';') : new Array();
			var index = vals.indexOf($self.data('day'));
			if(index > -1) {
				vals.splice(index, 1);
				$self.removeClass('nonfree').addClass('free');
			}
			else {
				vals.push($self.data('day'));
				$self.removeClass('free').addClass('nonfree');
			}
			$('textarea', agenda).val(vals.join(';'));
		}
		else {
			$.ajax({
				type: "POST",
				url: "<?php echo _ROOT_ADMIN; ?>agenda",
				data: { switchday: $self.data('day'), hotel: hotel, chambre: chambre }
			}).done(function(data) {
				$($self.parent()).before(data).remove();
				$('.haspopover', agenda).popover({
					animation: false,
					html: true,
					trigger: 'hover'
				});
			});
		}
	});
	
	$('body').on('click', '.agenda .frm_plage button', function() {
		var $self = $(this);
		var agenda = $self.parents('.agenda')[0];
		var frm = $self.parents('.frm_plage')[0];
		var hotel = $(agenda).is('[data-hotel]') ? $(agenda).data('hotel') : '';
		var chambre = $(agenda).is('[data-chambre]') ? $(agenda).data('chambre') : '';
		if(hotel == '' && chambre == '') {
			if($($('input', frm)[0]).val().match(/^([0-2][0-9]|3[01])\/(0[1-9]|1[012])\/([0-9]{4})$/) && $($('input', frm)[1]).val().match(/^([0-2][0-9]|3[01])\/(0[1-9]|1[012])\/([0-9]{4})$/)) {
				var a = $($('input', frm)[0]).val().match(/^([0-2][0-9]|3[01])\/(0[1-9]|1[012])\/([0-9]{4})$/);
				var b = $($('input', frm)[1]).val().match(/^([0-2][0-9]|3[01])\/(0[1-9]|1[012])\/([0-9]{4})$/);
				var debut = new Date(a[3], a[2] - 1, a[1]);
				var fin = new Date(b[3], b[2] - 1, b[1]);
				var action = $($('select', frm)[0]).val();
				var vals = $('textarea', agenda).val() != '' ? $('textarea', agenda).val().split(';') : new Array();
				if(debut > fin) {
					var tmp = debut;
					debut = fin;
					fin = tmp;
				}
				while(debut <= fin) {
					var d = debut.getFullYear() + '-' + ((debut.getMonth() + 1) < 10 ? '0' + (debut.getMonth() + 1) : (debut.getMonth() + 1)) + '-' + (debut.getDate() < 10 ? '0' + debut.getDate() : debut.getDate());
					if(action == 'nonfree') {
						if(vals.indexOf(d) == -1)
							vals.push(d);
						$('.cell.active[data-day="' + d + '"]', agenda).removeClass('free').addClass('nonfree');
					}
					else {
						if(vals.indexOf(d) > -1)
							vals.splice(vals.indexOf(d), 1);
						$('.cell.active[data-day="' + d + '"]', agenda).removeClass('nonfree').addClass('free');
					}
					debut.setDate(debut.getDate() + 1);
				}
				$('textarea', agenda).val(vals.join(';'));
			}
			else
				alert('Dates incorrectes (jj/mm/aaaa)');
		}
		else {
			$.ajax({
				type: "POST",
				url: "<?php echo _ROOT_ADMIN; ?>agenda",
				data: { plage: 1, action: $($('select', frm)[0]).val(), debut: $($('input', frm)[0]).val(), fin: $($('input', frm)[1]).val(), hotel: hotel, chambre: chambre }
			}).done(function(data) {
				if(data == '') {
					$.ajax({
						type: "POST",
						url: "<?php echo _ROOT_ADMIN; ?>agenda",
						data: { mois: $('.titre', agenda).data('current'), hotel: hotel, chambre: chambre }
					}).done(function(data) {
						$('.agenda_mois', agenda).html(data);
						$('.haspopover', agenda).popover({
							animation: false,
							html: true,
							trigger: 'hover'
						});
					});
				}
			});
		}
	});
	
	$('body').on('click', '.addsection', function(e) {
		e.preventDefault();
		var str = $($(this).data('pattern')).html();
		var tid = 'iteration' + Date.now();
		if($(this).is('[data-name]'))
			str = str.replace(/{{name}}/g, $(this).data('name'));
		var str = str.replace(/{{tid}}/g, tid);
		if($(this).is('[data-parentid]'))
			str = str.replace(/{{pid}}/g, $(this).data('parentid'));
		if($(this).is('[data-count]'))
			str = str.replace(/{{nb}}/g, $($(this).data('count'), $(this).parent()).get().length + 1);
		if( $(this).is('[data-target]') )
			$($(this).data('target')).append(str);
		else if($(this).is('a')) {
			/*if($($(this).parents('.form-group').get(0)).parent('[class*="list-sortable"]').get().length > 0)
				$($(this).parents('.form-group').get(0)).before(str);
			else if($($(this).parents('.form-group').get(0)).prev('[class*="list-sortable"]').get().length > 0)*/
				$($(this).parents('.form-group').get(0)).prev('[class*="list-sortable"]').append(str);
		}
		else
			$(this).before(str);
		$('.datepicker').datepicker({
			format: "dd/mm/yyyy",
			language: "fr",
			autoclose: true,
			todayHighlight: true
		});
		initSortable();
		if($(this).is('[data-function]')) {
			var func = $(this).data('function');
			window[func]();
		}
		/*initTinyMCE();
		if($('#gmap_' + tid).get().length == 1)
			initialize_map($('#gmap_' + tid).get(0));*/
	});

	$('body').on('click', '.block_parsed[data-type]', function() {
		var el = this;
		var val;
		try {
			val = JSON.parse($('input[type="hidden"]:first', el).val());
		}
		catch(e) {
			val = {};
		}
		$('#modal_pattern_' + $(el).data('type') + ' form')[0].reset();
		$('#modal_pattern_' + $(el).data('type') + ' form .images-list').html('');
		populateFormFromObject($('#modal_pattern_' + $(el).data('type') + ' form')[0], val);
		if($(el).data('type') == 'diaporama_element' || $(el).data('type') == 'gallery_element') {
			if(val.hasOwnProperty('image') && val['image'] != '') {
				$('#modal_pattern_' + $(el).data('type') + '_image').val(val['image']).change();
			}
		}
		if($(el).data('type') == 'map') {
			initialize_map($('#gmap-constructor')[0]);
		}
		mainTop = $(window).scrollTop();
		$(window).scrollTop(0);
		$('#modal_pattern_' + $(el).data('type')).modal();
		$(el).removeClass('block_parsed').addClass('block_to_parse');
	});

	$('.modal[id^="modal_pattern_"] form').submit(function(e) {
		$('.editor', this).each(function(i, el) {
			tinymce.get($(el).attr('id')).save();
		});
		e.preventDefault();
		$('.block_to_parse input[type="hidden"]').val(JSON.stringify($(this).serializeObject()));
		parseBlocksToParse();
		$($(this).parents('.modal').get(0)).modal('hide');
	});

	$('.modal[id^="modal_pattern_"]').on('hide.bs.modal', function() {
		$(window).scrollTop(mainTop);
		$('.block_to_parse').removeClass('block_to_parse').addClass('block_parsed');
	});

	
	
	$('body').on('click', '.dissmissable-block > .delete', function(e) {
		if($(this).hasClass('confirmation')) {
			$(this).confirmation({
				title: 'Ëtes-vous sûr ?',
				btnOkClass: 'btn-xs btn-success',
				btnCancelClass: 'btn-xs btn-danger',
				btnOkLabel: 'Oui',
				btnCancelLabel: 'Non',
				popout: true,
				singleton: true,
				onConfirm: function() {$(this).parent().remove();}
			}).confirmation('show');
		}
		else {
			e.preventDefault();
			$(this).parent().remove();

			if(typeof preview === 'function')
				preview();
		}
	});
	
	
	
	$('[data-toggle="confirmation"]').confirmation({
		title: 'Ëtes-vous sûr ?',
		btnOkClass: 'btn-success',
		btnCancelClass: 'btn-danger',
		btnOkLabel: 'Oui',
		btnCancelLabel: 'Non',
		popout: true
	});
	
	
	
	//lang
	$('body').on('click', '.lang_selector .btn', function() {
		$('.lang_selector .btn').removeClass('active');
		$('.lang_selector .btn[data-lang="' + $(this).data('lang') + '"]').addClass('active');
		$('.lang_toggle').addClass('hidden');
		$('.lang_toggle.lang_' + $(this).data('lang')).removeClass('hidden');
	});
	
	if($('.lang_selector').get().length > 0) {
		//init
		$('.btn:eq(0)', $('.lang_selector:eq(0)')).click();
	}


	//form submit check required visible / hidden fields
	$('form').on('submit', function() {
		var $form = $(this);
		var found = false;
		var i = 0;
		while(!found && i < $(':input.required', $form).get().length) {
			if($($(':input.required', $form).get(i)).val() == '') {
				found = true;
				var $field = $($(':input.required', $form).get(i));
				$($field.parents('.form-group').get(0)).addClass('has-error').append('<span class="help-block todeleteoninput">Ce champ doit être renseigné</span>');
				$field.one('keypress', function() {
					$($field.parents('.form-group').get(0)).removeClass('has-error').find('.todeleteoninput').remove();
				});
				if($field.is(':hidden'))
					$('.lang_selector .btn:not(.active)').click();
				$field.focus();
				return false;
			}
			i++;
		}
	});

	function createImgUploader(el) {
		var multiple = $(el).is('[data-single]') ? false : true;
		var name_images = $(el).is('[data-name-images]') ? $(el).data('name-images') : 'images';
		var name_legends = $(el).is('[data-name-legend]') ? $(el).data('name-legend') : 'images_legend';
		var featured = $(el).is('[data-featured]') ? parseInt($(el).data('featured')) : false;
		var imageuploader = new ss.SimpleUpload({
			button: $(el),
			url: $(el).data('url'),
			name: 'uploadfile',
			multiple: multiple,
			multipleSelect: multiple,
			hoverClass: 'hover',
			focusClass: 'hover',
			progressUrl: $(el).data('progress'),
			responseType: 'json',
			accept: 'image/*',
			allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			onSubmit: function(filename, extension, uploadBtn, fileSize) {
				var progress = document.createElement('div'),
				bar = document.createElement('div'),
				pct = document.createElement('span'),
				file = document.createElement('span'),
				wrapper = document.createElement('div'),
				progressBox = $($(uploadBtn).data('zoneprogress')).get(0);
				progress.className = 'progress';
				bar.className = 'progress-bar';
				pct.className = 'pct';
				wrapper.className = 'wrapper';
				file.innerHTML = filename;
				bar.appendChild(pct);
				bar.appendChild(file);
				progress.appendChild(bar); 
				wrapper.appendChild(progress);                                       
				progressBox.appendChild(wrapper);
				this.setProgressBar(bar);
				this.setPctBox(pct);
				this.setProgressContainer(wrapper);
			},
			onComplete: function(filename, response) {
				if(!response) {
					$($(el).data('zoneprogress')).append('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Une erreur est survenue los de l\'upload du fichier "' + filename + '"</div>');
					return false;
				}
				else {
					if(response.success) {
						var container = document.createElement('div');
						container.id = 'item' + cleanUrl(filename, true) + new Date().getTime();
						container.className = 'item';
						var str_featured = '';
						if(featured) {
							str_featured = '<div class="featured">';
							for(i = 1; i <= featured; i++)
								str_featured = str_featured + '<span class="featured' + i + '">' + i + '<input type="hidden" name="images_featured' + i + '[]" value="0"></span>';
							tr_featured = str_featured + '</div>';
						}
						$(container).css('background-image', 'url("' + _ROOT_ADMIN + response.file + '")').html('<div class="mask"><a class="fancybox_img" href="' + response.file + '"><span class="glyphicon glyphicon-zoom-in"></span></a><span class="edit_legend"><span class="glyphicon glyphicon-pencil"></span></span><span class="delete" data-delete="' + $(el).data('delete') + '"><span class="glyphicon glyphicon-trash"></span></span><input type="hidden" name="' + name_images + '[]" value="' + response.file + '"><?php foreach($_LANGS as $l => $ll) { echo '<input type="hidden" name="\' + name_legends + \'['.$l.'][]" value="">'; } ?>' + str_featured + '</div>');
						if(multiple)
							$($(el).data('zoneimages')).append(container);
						else
							$($(el).data('zoneimages')).html(container);
					}
					else {
						$($(el).data('zoneprogress')).append('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Une erreur est survenue los de l\'upload du fichier "' + filename + '" : ' + response.msg + '</div>');
						return false;
					}
				}
			}
		});
	}
	$('.upload_img').each(function(i, el) {
		createImgUploader(el)
	});
	$('body').on('click', '.zone_images .item .delete', function() {
		var item = $(this).parents('.item').get(0);
		$.ajax({
			type: "POST",
			url: $(this).data('delete'),
			data: { deletetmpimg: $('input[name="images[]"]', item).val()}
		});
		$($(this).parents('.item').get(0)).remove();
	});


	function createVideoUploader(el) {
		var multiple = $(el).is('[data-single]') ? false : true;
		var name_video = $(el).is('[data-name-video]') ? $(el).data('name-video') : 'videos';
		var name_video_legend = $(el).is('[data-video-legend]') ? $(el).data('video-legend') : 'video_legend';
		var featured = $(el).is('[data-featured]') ? parseInt($(el).data('featured')) : false;
		var videouploader = new ss.SimpleUpload({
			button: $(el),
			url: $(el).data('video-url'),
			name: 'uploadfile',
			multiple: multiple,
			multipleSelect: multiple,
			hoverClass: 'hover',
			focusClass: 'hover',
			progressUrl: $(el).data('progress'),
			responseType: 'json',
			accept: 'video/*',
			allowedExtensions: ['mp4', 'mpg4'],
			maxFileSize: 0,
			onSubmit: function(filename, extension, uploadBtn, fileSize) {
				var progress = document.createElement('div'),
				bar = document.createElement('div'),
				pct = document.createElement('span'),
				file = document.createElement('span'),
				wrapper = document.createElement('div'),
				progressBox = $($(uploadBtn).data('zoneprogressvid')).get(0);
				progress.className = 'progress';
				bar.className = 'progress-bar';
				pct.className = 'pct';
				wrapper.className = 'wrapper';
				file.innerHTML = filename;
				bar.appendChild(pct);
				bar.appendChild(file);
				progress.appendChild(bar); 
				wrapper.appendChild(progress);                                       
				progressBox.appendChild(wrapper);
				this.setProgressBar(bar);
				this.setPctBox(pct);
				this.setProgressContainer(wrapper);
				console.log('submit');
			},
			onComplete: function(filename, response) {
				console.log('complete');
				if(!response) {
					$($(el).data('zoneprogressvid')).append('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Une erreur est survenue los de l\'upload du fichier "' + filename + '"</div>');
					return false;
				}
				else {
					if(response.success) {
						console.log('success');
						var container = document.createElement('div');
						container.id = 'item' + cleanUrl(filename, true) + new Date().getTime();
						container.className = 'item';
						var str_featured = '';
						if(featured) {
							str_featured = '<div class="featured">';
							for(i = 1; i <= featured; i++)
								str_featured = str_featured + '<span class="featured' + i + '">' + i + '<input type="hidden" name="video_featured' + i + '[]" value="0"></span>';
							tr_featured = str_featured + '</div>';
						}
						$(container).html('<video loop muted width="200" height="150"  style="object-fit:fill !important;"><source src="' +_ROOT_ADMIN + response.file + '" type="video/mp4"></video><div class="mask"><a class="fancybox_img" href="' + response.file + '"><span class="glyphicon glyphicon-zoom-in"></span></a><span class="edit_legend"><span class="glyphicon glyphicon-pencil"></span></span><span class="delete" data-delete="' + $(el).data('delete') + '"><span class="glyphicon glyphicon-trash"></span></span><input type="hidden" name="' + name_video + '[]" value="' + response.file + '"><?php foreach($_LANGS as $l => $ll) { echo '<input type="hidden" name="\' + name_video_legend + \'['.$l.'][]" value="">'; } ?>' + str_featured + '</div>');
						if(multiple)
							$($(el).data('zonevideo')).append(container);
						else
							$($(el).data('zonevideo')).html(container);
					}
					else {
						$($(el).data('zoneprogressvid')).append('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Une erreur est survenue los de l\'upload du fichier "' + filename + '" : ' + response.msg + '</div>');
						return false;
					}
				}
			}
		});
	}
	$('.upload_video').each(function(i, el) {
		createVideoUploader(el)
	});
	$('body').on('click', '.zone_images .item .delete', function() {
		var item = $(this).parents('.item').get(0);
		$.ajax({
			type: "POST",
			url: $(this).data('delete'),
			data: { deletetmpvideo: $('input[name="videos[]"]', item).val()}
		});
		$($(this).parents('.item').get(0)).remove();
	});







	$('.map_constructor').each(function(i, el) {
		//if($(el).parents('#pattern_map').get().length == 0)
			initialize_map(el);
	});

	$(document).on('focusin', function(e) {
		if($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		}
	});

	$('#modal_pattern_map').on('shown.bs.modal', function(e) {
		var map = $('#gmap-constructor').data('map');
		var center = map.getCenter();
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	});

	$('#form_preview button').click(function() {
		var $form = $(this).parent();
		$.ajax({
			type: "POST",
			url: "<?php echo _ROOT_ADMIN; ?>?controller=pages&preview=1",
			data: $form.prev('form').serialize(),
			async: false
		}).done(function(data) {
			$form.find('input[name="id"]').val(data);
			$form.submit();
		});
	});

	$('body').on('click', '.zone_images .item .featured > span', function() {
		var item = $(this).parents('.item').get(0);
		var zone = $(this).parents('.zone_images').get(0);
		if($('input', this).val() == '0') {
			var classname = $(this).attr('class');
			$('.' + classname + ' input', zone).val('0');
			$('.' + classname, zone).removeClass('active');
			$('input', this).val('1');
			$(this).addClass('active');
		}
		else {
			$('input', this).val('0');
			$(this).removeClass('active');
		}
		$('.item', zone).each(function(i, el) {
			if($(el).find('.featured .active').get().length > 0)
				$(el).addClass('item_featured');
			else
				$(el).removeClass('item_featured');
		});
	});

	$('body').on('click', '.imagecolor', function() {
		if( !$($(this).data('image')).get().length ) {
			alert('Aucune image');
			return false;
		}
		var href = $($($(this).data('image'))[0]).attr('href');
		var image = (href.match(/^http(s|):\/\//g) ? '' : _PROTOCOL + _SERVER_NAME) + ( href.match(/^img\//g) ? _ROOT_ADMIN : '' ) + href;
		var image_src = '';
		var colorInput = $($(this).data('input'));
		var color = colorInput.val();
		toDataURL(
			image,
			function(dataUrl) {
				$.fancybox( 
					'<div id="imagecolorpicker" style="background:' + color + '"><div><input type="text" class="form-control" value="' + color + '"></div><img src="' + dataUrl + '"><canvas id="cs"></canvas></div>',
					{
						width: '90%',
						height: '90%',
						afterShow: function() {
							function useCanvas(el,image,callback){
								el.width = image.width;
								el.height = image.height; 
								el.getContext('2d')
								.drawImage(image, 0, 0, image.width, image.height);
								return callback();
							}
							function componentToHex(c) {
								var hex = c.toString(16);
								return hex.length == 1 ? "0" + hex : hex;
							}
							function rgbToHex(r, g, b) {
								return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
							}

							var img = $('#imagecolorpicker img'),
							canvas = $('#cs'),
							result = $('#imagecolorpicker input'),
							preview = $('#imagecolorpicker'),
							x = '',
							y = '';

							img.click(function(e){
								if(e.offsetX) {
									x = e.offsetX;
									y = e.offsetY; 
								}
								else if(e.layerX) {
									x = e.layerX;
									y = e.layerY;
								}
								useCanvas(canvas[0],img[0],function(){
									var p = canvas[0].getContext('2d').getImageData(x, y, 1, 1).data;
									var c = rgbToHex(p[0],p[1],p[2]);
									result.val(c);
									preview.css('background', c);
									colorInput.val(c);
									$.fancybox.close(true);
								});
							});

							img.mousemove(function(e){
								if(e.offsetX) {
									x = e.offsetX;
									y = e.offsetY; 
								}
								else if(e.layerX) {
									x = e.layerX;
									y = e.layerY;
								}
								useCanvas(canvas[0],img[0],function(){
									var p = canvas[0].getContext('2d').getImageData(x, y, 1, 1).data;
									var c = rgbToHex(p[0],p[1],p[2]);
									result.val(c);
									preview.css('background', c);
								});
							});

						}
					}
				);

			}
		);
	});
	
	
	initTinyMCE();

	initSortable();

	parseBlocksToParse();

	$(window).scroll();
});

$(window).scroll(function() {
	
	if($(window).scrollTop() > 0 && $(window).width() > 991)
		$('#back_to_top').addClass('show');
	else
		$('#back_to_top').removeClass('show');
});
</script>
</body>
</html>
