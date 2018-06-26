!function e(r,o,n){function i(s,a){if(!o[s]){if(!r[s]){var c="function"==typeof require&&require;if(!a&&c)return c(s,!0);if(t)return t(s,!0);var d=new Error("Cannot find module '"+s+"'");throw d.code="MODULE_NOT_FOUND",d}var u=o[s]={exports:{}};r[s][0].call(u.exports,function(e){var o=r[s][1][e];return i(o||e)},u,u.exports,e,r,o,n)}return o[s].exports}for(var t="function"==typeof require&&require,s=0;s<n.length;s++)i(n[s]);return i}({1:[function(e,r,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}var i=e("../static/device-matrix.json"),t=n(i),s=e("./util/detect/util-os"),a=n(s),c=e("./util/detect/util-browser.js"),d=n(c),u=!1,f=parseFloat(d.default.checkVersion()),l=a.default.os().toLocaleLowerCase(),w=parseFloat(a.default.osVersion());l&&(l=l.toLowerCase()),"Unknown"===w&&(w=Number.MAX_SAFE_INTEGER||9999),d.default.checkBot()?u=!0:"android"===l||"ios"===l?("ios"===l&&(d.default.checkSafari()||d.default.checkFacebook())&&w>=t.default.ios&&(u=!0),"android"===l&&(d.default.checkChrome()||d.default.checkFacebook())&&w>=t.default.android&&f>=t.default.chrome&&(u=!0)):(d.default.checkIE()&&f>=t.default.ie||d.default.checkFirefox()&&f>=t.default.firefox||d.default.checkChrome()&&f>=t.default.chrome||d.default.checkSafari()&&f>=t.default.safari||d.default.checkEdge())&&(u=!0),u||(window.location="unsupported.html")},{"../static/device-matrix.json":4,"./util/detect/util-browser.js":2,"./util/detect/util-os":3}],2:[function(e,r,o){"use strict";var n=navigator.userAgent.toLowerCase(),i=navigator.appVersion.toLowerCase(),t=navigator.vendor?navigator.vendor.toLowerCase():"",s=["facebookexternalhit","linkedinbot","google (+https://developers.google.com/+/web/snippet/)","facebot","https://developers.google.com/+/web/snippet/","twitterbot","tumblr","googlebot"];r.exports.checkIE=function(){var e=n.indexOf("msie")>=0,r=n.indexOf("trident/")>=0;return!(!e&&!r)},r.exports.checkBot=function(){var e=n.toLowerCase(),r=!1;return s.map(function(o){e.indexOf(o)>-1&&(r=!0)}),r};var a=function(){return n.indexOf("fban")>-1||n.indexOf("fbav")>-1};r.exports.checkFacebook=a,r.exports.checkFirefox=function(){return n.indexOf("firefox")>=0},r.exports.checkSafari=function(){return n.indexOf("safari")>=0&&t.indexOf("apple")>=0},r.exports.checkChrome=function(){return n.indexOf("chrome")>=0&&t.indexOf("google")>=0},r.exports.checkWindows=function(){return i.indexOf("win")>=0},r.exports.checkMac=function(){return i.indexOf("mac os x")>=0},r.exports.checkVersion=function(){var e,r=n.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i)||[];if(/trident/i.test(r[1]))return e=/\brv[ :]+(\d+)/g.exec(n)||[],e[1]||"";if("chrome"===r[1]&&null!=(e=n.match(/\bOPR\/(\d+)/)))return e[1];if(a()){var o=n.match(/(fbsv)\/?\s*(\d+)/i);if(o&&"fbsv"===o[1])return o[2];if("chrome"===r[1])return r[2]}return r=r[2]?[r[1],r[2]]:[navigator.appName,navigator.appVersion,"-?"],null!=(e=n.match(/version\/(\d+)/i))&&r.splice(1,1,e[1]),r[1]}},{}],3:[function(e,r,o){"use strict";var n=[{s:"Windows 10",r:/(Windows 10.0|Windows NT 10.0)/},{s:"Windows 8.1",r:/(Windows 8.1|Windows NT 6.3)/},{s:"Windows 8",r:/(Windows 8|Windows NT 6.2)/},{s:"Windows 7",r:/(Windows 7|Windows NT 6.1)/},{s:"Windows Vista",r:/Windows NT 6.0/},{s:"Windows Server 2003",r:/Windows NT 5.2/},{s:"Windows XP",r:/(Windows NT 5.1|Windows XP)/},{s:"Windows 2000",r:/(Windows NT 5.0|Windows 2000)/},{s:"Windows ME",r:/(Win 9x 4.90|Windows ME)/},{s:"Windows 98",r:/(Windows 98|Win98)/},{s:"Windows 95",r:/(Windows 95|Win95|Windows_95)/},{s:"Windows NT 4.0",r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},{s:"Windows CE",r:/Windows CE/},{s:"Windows 3.11",r:/Win16/},{s:"Android",r:/Android/},{s:"Open BSD",r:/OpenBSD/},{s:"Sun OS",r:/SunOS/},{s:"Linux",r:/(Linux|X11)/},{s:"iOS",r:/(iPhone|iPad|iPod)/},{s:"Mac OS X",r:/Mac OS X/},{s:"Mac OS",r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},{s:"QNX",r:/QNX/},{s:"UNIX",r:/UNIX/},{s:"BeOS",r:/BeOS/},{s:"OS/2",r:/OS\/2/},{s:"Search Bot",r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}],i=navigator.userAgent,t=function(){var e;for(var r in n){var o=n[r];if(o.r.test(i)){e=o.s;break}}return e}(),s=function(){var e="Unknown";switch(/Windows/.test(t)&&(e=t.replace(/^Windows /,""),t="Windows"),t){case"Mac OS X":e=/Mac OS X (10[\.\_\d]+)/.exec(i)[1];break;case"Android":e=/Android ([\.\_\d]+)/.exec(i)[1];break;case"iOS":e=/OS (\d+)_(\d+)_?(\d+)?/.exec(i),e=e[1]+"."+e[2]+"."+(0|e[3])}return e}();r.exports.os=function(){return t},r.exports.osVersion=function(){return s}},{}],4:[function(e,r,o){r.exports={chrome:35,firefox:30,safari:9,ie:10,edge:12,ios:9}},{}]},{},[1]);