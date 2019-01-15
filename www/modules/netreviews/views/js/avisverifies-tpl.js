/**
 * 2012-2017 NetReviews
 *
 *  @author    NetReviews SAS <contact@avis-verifies.com>
 *  @copyright 2017 NetReviews SAS
 *  @version   Release: $Revision: 7.6.1
 *  @license   NetReviews
 *  @date      04/05/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */
!function(e,t,i){"use strict";"function"==typeof define&&define.amd?define(i):"undefined"!=typeof module&&module.exports?module.exports=i():t.exports?t.exports=i():t[e]=i()}("Fingerprint2",this,function(){"use strict";var e=function(t){if(!(this instanceof e))return new e(t);var i={swfContainerId:"fingerprintjs2",swfPath:"flash/compiled/FontList.swf",detectScreenOrientation:!0,sortPluginsFor:[/palemoon/i],userDefinedFonts:[]};this.options=this.extend(t,i),this.nativeForEach=Array.prototype.forEach,this.nativeMap=Array.prototype.map};return e.prototype={extend:function(e,t){if(null==e)return t;for(var i in e)null!=e[i]&&t[i]!==e[i]&&(t[i]=e[i]);return t},get:function(e){var t=this,i={data:[],push:function(e){var i=e.key,a=e.value;"function"==typeof t.options.preprocessor&&(a=t.options.preprocessor(i,a)),this.data.push({key:i,value:a})}};i=this.userAgentKey(i),i=this.languageKey(i),i=this.colorDepthKey(i),i=this.pixelRatioKey(i),i=this.hardwareConcurrencyKey(i),i=this.screenResolutionKey(i),i=this.availableScreenResolutionKey(i),i=this.timezoneOffsetKey(i),i=this.sessionStorageKey(i),i=this.localStorageKey(i),i=this.indexedDbKey(i),i=this.addBehaviorKey(i),i=this.openDatabaseKey(i),i=this.cpuClassKey(i),i=this.platformKey(i),i=this.doNotTrackKey(i),i=this.pluginsKey(i),i=this.canvasKey(i),i=this.webglKey(i),i=this.adBlockKey(i),i=this.hasLiedLanguagesKey(i),i=this.hasLiedResolutionKey(i),i=this.hasLiedOsKey(i),i=this.hasLiedBrowserKey(i),i=this.touchSupportKey(i),i=this.customEntropyFunction(i),this.fontsKey(i,function(i){var a=[];t.each(i.data,function(e){var t=e.value;"undefined"!=typeof e.value.join&&(t=e.value.join(";")),a.push(t)});var r=t.x64hash128(a.join("~~~"),31);return e(r,i.data)})},customEntropyFunction:function(e){return"function"==typeof this.options.customFunction&&e.push({key:"custom",value:this.options.customFunction()}),e},userAgentKey:function(e){return this.options.excludeUserAgent||e.push({key:"user_agent",value:this.getUserAgent()}),e},getUserAgent:function(){return navigator.userAgent},languageKey:function(e){return this.options.excludeLanguage||e.push({key:"language",value:navigator.language||navigator.userLanguage||navigator.browserLanguage||navigator.systemLanguage||""}),e},colorDepthKey:function(e){return this.options.excludeColorDepth||e.push({key:"color_depth",value:screen.colorDepth||-1}),e},pixelRatioKey:function(e){return this.options.excludePixelRatio||e.push({key:"pixel_ratio",value:this.getPixelRatio()}),e},getPixelRatio:function(){return window.devicePixelRatio||""},screenResolutionKey:function(e){return this.options.excludeScreenResolution?e:this.getScreenResolution(e)},getScreenResolution:function(e){var t;return t=this.options.detectScreenOrientation&&screen.height>screen.width?[screen.height,screen.width]:[screen.width,screen.height],"undefined"!=typeof t&&e.push({key:"resolution",value:t}),e},availableScreenResolutionKey:function(e){return this.options.excludeAvailableScreenResolution?e:this.getAvailableScreenResolution(e)},getAvailableScreenResolution:function(e){var t;return screen.availWidth&&screen.availHeight&&(t=this.options.detectScreenOrientation?screen.availHeight>screen.availWidth?[screen.availHeight,screen.availWidth]:[screen.availWidth,screen.availHeight]:[screen.availHeight,screen.availWidth]),"undefined"!=typeof t&&e.push({key:"available_resolution",value:t}),e},timezoneOffsetKey:function(e){return this.options.excludeTimezoneOffset||e.push({key:"timezone_offset",value:(new Date).getTimezoneOffset()}),e},sessionStorageKey:function(e){return!this.options.excludeSessionStorage&&this.hasSessionStorage()&&e.push({key:"session_storage",value:1}),e},localStorageKey:function(e){return!this.options.excludeSessionStorage&&this.hasLocalStorage()&&e.push({key:"local_storage",value:1}),e},indexedDbKey:function(e){return!this.options.excludeIndexedDB&&this.hasIndexedDB()&&e.push({key:"indexed_db",value:1}),e},addBehaviorKey:function(e){return document.body&&!this.options.excludeAddBehavior&&document.body.addBehavior&&e.push({key:"add_behavior",value:1}),e},openDatabaseKey:function(e){return!this.options.excludeOpenDatabase&&window.openDatabase&&e.push({key:"open_database",value:1}),e},cpuClassKey:function(e){return this.options.excludeCpuClass||e.push({key:"cpu_class",value:this.getNavigatorCpuClass()}),e},platformKey:function(e){return this.options.excludePlatform||e.push({key:"navigator_platform",value:this.getNavigatorPlatform()}),e},doNotTrackKey:function(e){return this.options.excludeDoNotTrack||e.push({key:"do_not_track",value:this.getDoNotTrack()}),e},canvasKey:function(e){return!this.options.excludeCanvas&&this.isCanvasSupported()&&e.push({key:"canvas",value:this.getCanvasFp()}),e},webglKey:function(e){return this.options.excludeWebGL?e:this.isWebGlSupported()?(e.push({key:"webgl",value:this.getWebglFp()}),e):e},adBlockKey:function(e){return this.options.excludeAdBlock||e.push({key:"adblock",value:this.getAdBlock()}),e},hasLiedLanguagesKey:function(e){return this.options.excludeHasLiedLanguages||e.push({key:"has_lied_languages",value:this.getHasLiedLanguages()}),e},hasLiedResolutionKey:function(e){return this.options.excludeHasLiedResolution||e.push({key:"has_lied_resolution",value:this.getHasLiedResolution()}),e},hasLiedOsKey:function(e){return this.options.excludeHasLiedOs||e.push({key:"has_lied_os",value:this.getHasLiedOs()}),e},hasLiedBrowserKey:function(e){return this.options.excludeHasLiedBrowser||e.push({key:"has_lied_browser",value:this.getHasLiedBrowser()}),e},fontsKey:function(e,t){return this.options.excludeJsFonts?this.flashFontsKey(e,t):this.jsFontsKey(e,t)},flashFontsKey:function(e,t){return this.options.excludeFlashFonts?t(e):this.hasSwfObjectLoaded()&&this.hasMinFlashInstalled()?"undefined"==typeof this.options.swfPath?t(e):void this.loadSwfAndDetectFonts(function(i){e.push({key:"swf_fonts",value:i.join(";")}),t(e)}):t(e)},jsFontsKey:function(e,t){var i=this;return setTimeout(function(){var a=["monospace","sans-serif","serif"],r=["Andale Mono","Arial","Arial Black","Arial Hebrew","Arial MT","Arial Narrow","Arial Rounded MT Bold","Arial Unicode MS","Bitstream Vera Sans Mono","Book Antiqua","Bookman Old Style","Calibri","Cambria","Cambria Math","Century","Century Gothic","Century Schoolbook","Comic Sans","Comic Sans MS","Consolas","Courier","Courier New","Garamond","Geneva","Georgia","Helvetica","Helvetica Neue","Impact","Lucida Bright","Lucida Calligraphy","Lucida Console","Lucida Fax","LUCIDA GRANDE","Lucida Handwriting","Lucida Sans","Lucida Sans Typewriter","Lucida Sans Unicode","Microsoft Sans Serif","Monaco","Monotype Corsiva","MS Gothic","MS Outlook","MS PGothic","MS Reference Sans Serif","MS Sans Serif","MS Serif","MYRIAD","MYRIAD PRO","Palatino","Palatino Linotype","Segoe Print","Segoe Script","Segoe UI","Segoe UI Light","Segoe UI Semibold","Segoe UI Symbol","Tahoma","Times","Times New Roman","Times New Roman PS","Trebuchet MS","Verdana","Wingdings","Wingdings 2","Wingdings 3"],n=["Abadi MT Condensed Light","Academy Engraved LET","ADOBE CASLON PRO","Adobe Garamond","ADOBE GARAMOND PRO","Agency FB","Aharoni","Albertus Extra Bold","Albertus Medium","Algerian","Amazone BT","American Typewriter","American Typewriter Condensed","AmerType Md BT","Andalus","Angsana New","AngsanaUPC","Antique Olive","Aparajita","Apple Chancery","Apple Color Emoji","Apple SD Gothic Neo","Arabic Typesetting","ARCHER","ARNO PRO","Arrus BT","Aurora Cn BT","AvantGarde Bk BT","AvantGarde Md BT","AVENIR","Ayuthaya","Bandy","Bangla Sangam MN","Bank Gothic","BankGothic Md BT","Baskerville","Baskerville Old Face","Batang","BatangChe","Bauer Bodoni","Bauhaus 93","Bazooka","Bell MT","Bembo","Benguiat Bk BT","Berlin Sans FB","Berlin Sans FB Demi","Bernard MT Condensed","BernhardFashion BT","BernhardMod BT","Big Caslon","BinnerD","Blackadder ITC","BlairMdITC TT","Bodoni 72","Bodoni 72 Oldstyle","Bodoni 72 Smallcaps","Bodoni MT","Bodoni MT Black","Bodoni MT Condensed","Bodoni MT Poster Compressed","Bookshelf Symbol 7","Boulder","Bradley Hand","Bradley Hand ITC","Bremen Bd BT","Britannic Bold","Broadway","Browallia New","BrowalliaUPC","Brush Script MT","Californian FB","Calisto MT","Calligrapher","Candara","CaslonOpnface BT","Castellar","Centaur","Cezanne","CG Omega","CG Times","Chalkboard","Chalkboard SE","Chalkduster","Charlesworth","Charter Bd BT","Charter BT","Chaucer","ChelthmITC Bk BT","Chiller","Clarendon","Clarendon Condensed","CloisterBlack BT","Cochin","Colonna MT","Constantia","Cooper Black","Copperplate","Copperplate Gothic","Copperplate Gothic Bold","Copperplate Gothic Light","CopperplGoth Bd BT","Corbel","Cordia New","CordiaUPC","Cornerstone","Coronet","Cuckoo","Curlz MT","DaunPenh","Dauphin","David","DB LCD Temp","DELICIOUS","Denmark","DFKai-SB","Didot","DilleniaUPC","DIN","DokChampa","Dotum","DotumChe","Ebrima","Edwardian Script ITC","Elephant","English 111 Vivace BT","Engravers MT","EngraversGothic BT","Eras Bold ITC","Eras Demi ITC","Eras Light ITC","Eras Medium ITC","EucrosiaUPC","Euphemia","Euphemia UCAS","EUROSTILE","Exotc350 Bd BT","FangSong","Felix Titling","Fixedsys","FONTIN","Footlight MT Light","Forte","FrankRuehl","Fransiscan","Freefrm721 Blk BT","FreesiaUPC","Freestyle Script","French Script MT","FrnkGothITC Bk BT","Fruitger","FRUTIGER","Futura","Futura Bk BT","Futura Lt BT","Futura Md BT","Futura ZBlk BT","FuturaBlack BT","Gabriola","Galliard BT","Gautami","Geeza Pro","Geometr231 BT","Geometr231 Hv BT","Geometr231 Lt BT","GeoSlab 703 Lt BT","GeoSlab 703 XBd BT","Gigi","Gill Sans","Gill Sans MT","Gill Sans MT Condensed","Gill Sans MT Ext Condensed Bold","Gill Sans Ultra Bold","Gill Sans Ultra Bold Condensed","Gisha","Gloucester MT Extra Condensed","GOTHAM","GOTHAM BOLD","Goudy Old Style","Goudy Stout","GoudyHandtooled BT","GoudyOLSt BT","Gujarati Sangam MN","Gulim","GulimChe","Gungsuh","GungsuhChe","Gurmukhi MN","Haettenschweiler","Harlow Solid Italic","Harrington","Heather","Heiti SC","Heiti TC","HELV","Herald","High Tower Text","Hiragino Kaku Gothic ProN","Hiragino Mincho ProN","Hoefler Text","Humanst 521 Cn BT","Humanst521 BT","Humanst521 Lt BT","Imprint MT Shadow","Incised901 Bd BT","Incised901 BT","Incised901 Lt BT","INCONSOLATA","Informal Roman","Informal011 BT","INTERSTATE","IrisUPC","Iskoola Pota","JasmineUPC","Jazz LET","Jenson","Jester","Jokerman","Juice ITC","Kabel Bk BT","Kabel Ult BT","Kailasa","KaiTi","Kalinga","Kannada Sangam MN","Kartika","Kaufmann Bd BT","Kaufmann BT","Khmer UI","KodchiangUPC","Kokila","Korinna BT","Kristen ITC","Krungthep","Kunstler Script","Lao UI","Latha","Leelawadee","Letter Gothic","Levenim MT","LilyUPC","Lithograph","Lithograph Light","Long Island","Lydian BT","Magneto","Maiandra GD","Malayalam Sangam MN","Malgun Gothic","Mangal","Marigold","Marion","Marker Felt","Market","Marlett","Matisse ITC","Matura MT Script Capitals","Meiryo","Meiryo UI","Microsoft Himalaya","Microsoft JhengHei","Microsoft New Tai Lue","Microsoft PhagsPa","Microsoft Tai Le","Microsoft Uighur","Microsoft YaHei","Microsoft Yi Baiti","MingLiU","MingLiU_HKSCS","MingLiU_HKSCS-ExtB","MingLiU-ExtB","Minion","Minion Pro","Miriam","Miriam Fixed","Mistral","Modern","Modern No. 20","Mona Lisa Solid ITC TT","Mongolian Baiti","MONO","MoolBoran","Mrs Eaves","MS LineDraw","MS Mincho","MS PMincho","MS Reference Specialty","MS UI Gothic","MT Extra","MUSEO","MV Boli","Nadeem","Narkisim","NEVIS","News Gothic","News GothicMT","NewsGoth BT","Niagara Engraved","Niagara Solid","Noteworthy","NSimSun","Nyala","OCR A Extended","Old Century","Old English Text MT","Onyx","Onyx BT","OPTIMA","Oriya Sangam MN","OSAKA","OzHandicraft BT","Palace Script MT","Papyrus","Parchment","Party LET","Pegasus","Perpetua","Perpetua Titling MT","PetitaBold","Pickwick","Plantagenet Cherokee","Playbill","PMingLiU","PMingLiU-ExtB","Poor Richard","Poster","PosterBodoni BT","PRINCETOWN LET","Pristina","PTBarnum BT","Pythagoras","Raavi","Rage Italic","Ravie","Ribbon131 Bd BT","Rockwell","Rockwell Condensed","Rockwell Extra Bold","Rod","Roman","Sakkal Majalla","Santa Fe LET","Savoye LET","Sceptre","Script","Script MT Bold","SCRIPTINA","Serifa","Serifa BT","Serifa Th BT","ShelleyVolante BT","Sherwood","Shonar Bangla","Showcard Gothic","Shruti","Signboard","SILKSCREEN","SimHei","Simplified Arabic","Simplified Arabic Fixed","SimSun","SimSun-ExtB","Sinhala Sangam MN","Sketch Rockwell","Skia","Small Fonts","Snap ITC","Snell Roundhand","Socket","Souvenir Lt BT","Staccato222 BT","Steamer","Stencil","Storybook","Styllo","Subway","Swis721 BlkEx BT","Swiss911 XCm BT","Sylfaen","Synchro LET","System","Tamil Sangam MN","Technical","Teletype","Telugu Sangam MN","Tempus Sans ITC","Terminal","Thonburi","Traditional Arabic","Trajan","TRAJAN PRO","Tristan","Tubular","Tunga","Tw Cen MT","Tw Cen MT Condensed","Tw Cen MT Condensed Extra Bold","TypoUpright BT","Unicorn","Univers","Univers CE 55 Medium","Univers Condensed","Utsaah","Vagabond","Vani","Vijaya","Viner Hand ITC","VisualUI","Vivaldi","Vladimir Script","Vrinda","Westminster","WHITNEY","Wide Latin","ZapfEllipt BT","ZapfHumnst BT","ZapfHumnst Dm BT","Zapfino","Zurich BlkEx BT","Zurich Ex BT","ZWAdobeF"];i.options.extendedJsFonts&&(r=r.concat(n)),r=r.concat(i.options.userDefinedFonts);var o="mmmmmmmmmmlli",s="72px",l=document.getElementsByTagName("body")[0],h=document.createElement("div"),u=document.createElement("div"),c={},d={},g=function(){var e=document.createElement("span");return e.style.position="absolute",e.style.left="-9999px",e.style.fontSize=s,e.style.lineHeight="normal",e.innerHTML=o,e},p=function(e,t){var i=g();return i.style.fontFamily="'"+e+"',"+t,i},f=function(){for(var e=[],t=0,i=a.length;t<i;t++){var r=g();r.style.fontFamily=a[t],h.appendChild(r),e.push(r)}return e},m=function(){for(var e={},t=0,i=r.length;t<i;t++){for(var n=[],o=0,s=a.length;o<s;o++){var l=p(r[t],a[o]);u.appendChild(l),n.push(l)}e[r[t]]=n}return e},T=function(e){for(var t=!1,i=0;i<a.length;i++)if(t=e[i].offsetWidth!==c[a[i]]||e[i].offsetHeight!==d[a[i]])return t;return t},S=f();l.appendChild(h);for(var x=0,v=a.length;x<v;x++)c[a[x]]=S[x].offsetWidth,d[a[x]]=S[x].offsetHeight;var E=m();l.appendChild(u);for(var M=[],A=0,y=r.length;A<y;A++)T(E[r[A]])&&M.push(r[A]);l.removeChild(u),l.removeChild(h),e.push({key:"js_fonts",value:M}),t(e)},1)},pluginsKey:function(e){return this.options.excludePlugins||(this.isIE()?this.options.excludeIEPlugins||e.push({key:"ie_plugins",value:this.getIEPlugins()}):e.push({key:"regular_plugins",value:this.getRegularPlugins()})),e},getRegularPlugins:function(){for(var e=[],t=0,i=navigator.plugins.length;t<i;t++)e.push(navigator.plugins[t]);return this.pluginsShouldBeSorted()&&(e=e.sort(function(e,t){return e.name>t.name?1:e.name<t.name?-1:0})),this.map(e,function(e){var t=this.map(e,function(e){return[e.type,e.suffixes].join("~")}).join(",");return[e.name,e.description,t].join("::")},this)},getIEPlugins:function(){var e=[];if(Object.getOwnPropertyDescriptor&&Object.getOwnPropertyDescriptor(window,"ActiveXObject")||"ActiveXObject"in window){var t=["AcroPDF.PDF","Adodb.Stream","AgControl.AgControl","DevalVRXCtrl.DevalVRXCtrl.1","MacromediaFlashPaper.MacromediaFlashPaper","Msxml2.DOMDocument","Msxml2.XMLHTTP","PDF.PdfCtrl","QuickTime.QuickTime","QuickTimeCheckObject.QuickTimeCheck.1","RealPlayer","RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)","RealVideo.RealVideo(tm) ActiveX Control (32-bit)","Scripting.Dictionary","SWCtl.SWCtl","Shell.UIHelper","ShockwaveFlash.ShockwaveFlash","Skype.Detection","TDCCtl.TDCCtl","WMPlayer.OCX","rmocx.RealPlayer G2 Control","rmocx.RealPlayer G2 Control.1"];e=this.map(t,function(e){try{return new ActiveXObject(e),e}catch(t){return null}})}return navigator.plugins&&(e=e.concat(this.getRegularPlugins())),e},pluginsShouldBeSorted:function(){for(var e=!1,t=0,i=this.options.sortPluginsFor.length;t<i;t++){var a=this.options.sortPluginsFor[t];if(navigator.userAgent.match(a)){e=!0;break}}return e},touchSupportKey:function(e){return this.options.excludeTouchSupport||e.push({key:"touch_support",value:this.getTouchSupport()}),e},hardwareConcurrencyKey:function(e){return this.options.excludeHardwareConcurrency||e.push({key:"hardware_concurrency",value:this.getHardwareConcurrency()}),e},hasSessionStorage:function(){try{return!!window.sessionStorage}catch(e){return!0}},hasLocalStorage:function(){try{return!!window.localStorage}catch(e){return!0}},hasIndexedDB:function(){try{return!!window.indexedDB}catch(e){return!0}},getHardwareConcurrency:function(){return navigator.hardwareConcurrency?navigator.hardwareConcurrency:"unknown"},getNavigatorCpuClass:function(){return navigator.cpuClass?navigator.cpuClass:"unknown"},getNavigatorPlatform:function(){return navigator.platform?navigator.platform:"unknown"},getDoNotTrack:function(){return navigator.doNotTrack?navigator.doNotTrack:navigator.msDoNotTrack?navigator.msDoNotTrack:window.doNotTrack?window.doNotTrack:"unknown"},getTouchSupport:function(){var e=0,t=!1;"undefined"!=typeof navigator.maxTouchPoints?e=navigator.maxTouchPoints:"undefined"!=typeof navigator.msMaxTouchPoints&&(e=navigator.msMaxTouchPoints);try{document.createEvent("TouchEvent"),t=!0}catch(i){}var a="ontouchstart"in window;return[e,t,a]},getCanvasFp:function(){var e=[],t=document.createElement("canvas");t.width=2e3,t.height=200,t.style.display="inline";var i=t.getContext("2d");return i.rect(0,0,10,10),i.rect(2,2,6,6),e.push("canvas winding:"+(i.isPointInPath(5,5,"evenodd")===!1?"yes":"no")),i.textBaseline="alphabetic",i.fillStyle="#f60",i.fillRect(125,1,62,20),i.fillStyle="#069",this.options.dontUseFakeFontInCanvas?i.font="11pt Arial":i.font="11pt no-real-font-123",i.fillText("Cwm fjordbank glyphs vext quiz, \ud83d\ude03",2,15),i.fillStyle="rgba(102, 204, 0, 0.2)",i.font="18pt Arial",i.fillText("Cwm fjordbank glyphs vext quiz, \ud83d\ude03",4,45),i.globalCompositeOperation="multiply",i.fillStyle="rgb(255,0,255)",i.beginPath(),i.arc(50,50,50,0,2*Math.PI,!0),i.closePath(),i.fill(),i.fillStyle="rgb(0,255,255)",i.beginPath(),i.arc(100,50,50,0,2*Math.PI,!0),i.closePath(),i.fill(),i.fillStyle="rgb(255,255,0)",i.beginPath(),i.arc(75,100,50,0,2*Math.PI,!0),i.closePath(),i.fill(),i.fillStyle="rgb(255,0,255)",i.arc(75,75,75,0,2*Math.PI,!0),i.arc(75,75,25,0,2*Math.PI,!0),i.fill("evenodd"),e.push("canvas fp:"+t.toDataURL()),e.join("~")},getWebglFp:function(){var e,t=function(t){return e.clearColor(0,0,0,1),e.enable(e.DEPTH_TEST),e.depthFunc(e.LEQUAL),e.clear(e.COLOR_BUFFER_BIT|e.DEPTH_BUFFER_BIT),"["+t[0]+", "+t[1]+"]"},i=function(e){var t,i=e.getExtension("EXT_texture_filter_anisotropic")||e.getExtension("WEBKIT_EXT_texture_filter_anisotropic")||e.getExtension("MOZ_EXT_texture_filter_anisotropic");return i?(t=e.getParameter(i.MAX_TEXTURE_MAX_ANISOTROPY_EXT),0===t&&(t=2),t):null};if(e=this.getWebglCanvas(),!e)return null;var a=[],r="attribute vec2 attrVertex;varying vec2 varyinTexCoordinate;uniform vec2 uniformOffset;void main(){varyinTexCoordinate=attrVertex+uniformOffset;gl_Position=vec4(attrVertex,0,1);}",n="precision mediump float;varying vec2 varyinTexCoordinate;void main() {gl_FragColor=vec4(varyinTexCoordinate,0,1);}",o=e.createBuffer();e.bindBuffer(e.ARRAY_BUFFER,o);var s=new Float32Array([-.2,-.9,0,.4,-.26,0,0,.732134444,0]);e.bufferData(e.ARRAY_BUFFER,s,e.STATIC_DRAW),o.itemSize=3,o.numItems=3;var l=e.createProgram(),h=e.createShader(e.VERTEX_SHADER);e.shaderSource(h,r),e.compileShader(h);var u=e.createShader(e.FRAGMENT_SHADER);e.shaderSource(u,n),e.compileShader(u),e.attachShader(l,h),e.attachShader(l,u),e.linkProgram(l),e.useProgram(l),l.vertexPosAttrib=e.getAttribLocation(l,"attrVertex"),l.offsetUniform=e.getUniformLocation(l,"uniformOffset"),e.enableVertexAttribArray(l.vertexPosArray),e.vertexAttribPointer(l.vertexPosAttrib,o.itemSize,e.FLOAT,!1,0,0),e.uniform2f(l.offsetUniform,1,1),e.drawArrays(e.TRIANGLE_STRIP,0,o.numItems),null!=e.canvas&&a.push(e.canvas.toDataURL()),a.push("extensions:"+e.getSupportedExtensions().join(";")),a.push("webgl aliased line width range:"+t(e.getParameter(e.ALIASED_LINE_WIDTH_RANGE))),a.push("webgl aliased point size range:"+t(e.getParameter(e.ALIASED_POINT_SIZE_RANGE))),a.push("webgl alpha bits:"+e.getParameter(e.ALPHA_BITS)),a.push("webgl antialiasing:"+(e.getContextAttributes().antialias?"yes":"no")),a.push("webgl blue bits:"+e.getParameter(e.BLUE_BITS)),a.push("webgl depth bits:"+e.getParameter(e.DEPTH_BITS)),a.push("webgl green bits:"+e.getParameter(e.GREEN_BITS)),a.push("webgl max anisotropy:"+i(e)),a.push("webgl max combined texture image units:"+e.getParameter(e.MAX_COMBINED_TEXTURE_IMAGE_UNITS)),a.push("webgl max cube map texture size:"+e.getParameter(e.MAX_CUBE_MAP_TEXTURE_SIZE)),a.push("webgl max fragment uniform vectors:"+e.getParameter(e.MAX_FRAGMENT_UNIFORM_VECTORS)),a.push("webgl max render buffer size:"+e.getParameter(e.MAX_RENDERBUFFER_SIZE)),a.push("webgl max texture image units:"+e.getParameter(e.MAX_TEXTURE_IMAGE_UNITS)),a.push("webgl max texture size:"+e.getParameter(e.MAX_TEXTURE_SIZE)),a.push("webgl max varying vectors:"+e.getParameter(e.MAX_VARYING_VECTORS)),a.push("webgl max vertex attribs:"+e.getParameter(e.MAX_VERTEX_ATTRIBS)),a.push("webgl max vertex texture image units:"+e.getParameter(e.MAX_VERTEX_TEXTURE_IMAGE_UNITS)),a.push("webgl max vertex uniform vectors:"+e.getParameter(e.MAX_VERTEX_UNIFORM_VECTORS)),a.push("webgl max viewport dims:"+t(e.getParameter(e.MAX_VIEWPORT_DIMS))),a.push("webgl red bits:"+e.getParameter(e.RED_BITS)),a.push("webgl renderer:"+e.getParameter(e.RENDERER)),a.push("webgl shading language version:"+e.getParameter(e.SHADING_LANGUAGE_VERSION)),a.push("webgl stencil bits:"+e.getParameter(e.STENCIL_BITS)),a.push("webgl vendor:"+e.getParameter(e.VENDOR)),a.push("webgl version:"+e.getParameter(e.VERSION));try{var c=e.getExtension("WEBGL_debug_renderer_info");c&&(a.push("webgl unmasked vendor:"+e.getParameter(c.UNMASKED_VENDOR_WEBGL)),a.push("webgl unmasked renderer:"+e.getParameter(c.UNMASKED_RENDERER_WEBGL)))}catch(d){}return e.getShaderPrecisionFormat?(a.push("webgl vertex shader high float precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_FLOAT).precision),a.push("webgl vertex shader high float precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_FLOAT).rangeMin),a.push("webgl vertex shader high float precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_FLOAT).rangeMax),a.push("webgl vertex shader medium float precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_FLOAT).precision),a.push("webgl vertex shader medium float precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_FLOAT).rangeMin),a.push("webgl vertex shader medium float precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_FLOAT).rangeMax),a.push("webgl vertex shader low float precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_FLOAT).precision),a.push("webgl vertex shader low float precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_FLOAT).rangeMin),a.push("webgl vertex shader low float precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_FLOAT).rangeMax),a.push("webgl fragment shader high float precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_FLOAT).precision),a.push("webgl fragment shader high float precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_FLOAT).rangeMin),a.push("webgl fragment shader high float precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_FLOAT).rangeMax),a.push("webgl fragment shader medium float precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_FLOAT).precision),a.push("webgl fragment shader medium float precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_FLOAT).rangeMin),a.push("webgl fragment shader medium float precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_FLOAT).rangeMax),a.push("webgl fragment shader low float precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_FLOAT).precision),a.push("webgl fragment shader low float precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_FLOAT).rangeMin),a.push("webgl fragment shader low float precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_FLOAT).rangeMax),a.push("webgl vertex shader high int precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_INT).precision),a.push("webgl vertex shader high int precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_INT).rangeMin),a.push("webgl vertex shader high int precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.HIGH_INT).rangeMax),a.push("webgl vertex shader medium int precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_INT).precision),a.push("webgl vertex shader medium int precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_INT).rangeMin),a.push("webgl vertex shader medium int precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.MEDIUM_INT).rangeMax),a.push("webgl vertex shader low int precision:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_INT).precision),a.push("webgl vertex shader low int precision rangeMin:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_INT).rangeMin),a.push("webgl vertex shader low int precision rangeMax:"+e.getShaderPrecisionFormat(e.VERTEX_SHADER,e.LOW_INT).rangeMax),a.push("webgl fragment shader high int precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_INT).precision),a.push("webgl fragment shader high int precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_INT).rangeMin),a.push("webgl fragment shader high int precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.HIGH_INT).rangeMax),a.push("webgl fragment shader medium int precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_INT).precision),a.push("webgl fragment shader medium int precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_INT).rangeMin),a.push("webgl fragment shader medium int precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.MEDIUM_INT).rangeMax),a.push("webgl fragment shader low int precision:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_INT).precision),a.push("webgl fragment shader low int precision rangeMin:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_INT).rangeMin),a.push("webgl fragment shader low int precision rangeMax:"+e.getShaderPrecisionFormat(e.FRAGMENT_SHADER,e.LOW_INT).rangeMax),a.join("~")):a.join("~")},getAdBlock:function(){var e=document.createElement("div");e.innerHTML="&nbsp;",e.className="adsbox";var t=!1;try{document.body.appendChild(e),t=0===document.getElementsByClassName("adsbox")[0].offsetHeight,document.body.removeChild(e)}catch(i){t=!1}return t},getHasLiedLanguages:function(){if("undefined"!=typeof navigator.languages)try{var e=navigator.languages[0].substr(0,2);if(e!==navigator.language.substr(0,2))return!0}catch(t){return!0}return!1},getHasLiedResolution:function(){return screen.width<screen.availWidth||screen.height<screen.availHeight},getHasLiedOs:function(){var e,t=navigator.userAgent.toLowerCase(),i=navigator.oscpu,a=navigator.platform.toLowerCase();e=t.indexOf("windows phone")>=0?"Windows Phone":t.indexOf("win")>=0?"Windows":t.indexOf("android")>=0?"Android":t.indexOf("linux")>=0?"Linux":t.indexOf("iphone")>=0||t.indexOf("ipad")>=0?"iOS":t.indexOf("mac")>=0?"Mac":"Other";var r;if(r="ontouchstart"in window||navigator.maxTouchPoints>0||navigator.msMaxTouchPoints>0,r&&"Windows Phone"!==e&&"Android"!==e&&"iOS"!==e&&"Other"!==e)return!0;if("undefined"!=typeof i){if(i=i.toLowerCase(),i.indexOf("win")>=0&&"Windows"!==e&&"Windows Phone"!==e)return!0;if(i.indexOf("linux")>=0&&"Linux"!==e&&"Android"!==e)return!0;if(i.indexOf("mac")>=0&&"Mac"!==e&&"iOS"!==e)return!0;if(0===i.indexOf("win")&&0===i.indexOf("linux")&&i.indexOf("mac")>=0&&"other"!==e)return!0}return a.indexOf("win")>=0&&"Windows"!==e&&"Windows Phone"!==e||((a.indexOf("linux")>=0||a.indexOf("android")>=0||a.indexOf("pike")>=0)&&"Linux"!==e&&"Android"!==e||((a.indexOf("mac")>=0||a.indexOf("ipad")>=0||a.indexOf("ipod")>=0||a.indexOf("iphone")>=0)&&"Mac"!==e&&"iOS"!==e||(0===a.indexOf("win")&&0===a.indexOf("linux")&&a.indexOf("mac")>=0&&"other"!==e||"undefined"==typeof navigator.plugins&&"Windows"!==e&&"Windows Phone"!==e)))},getHasLiedBrowser:function(){var e,t=navigator.userAgent.toLowerCase(),i=navigator.productSub;if(e=t.indexOf("firefox")>=0?"Firefox":t.indexOf("opera")>=0||t.indexOf("opr")>=0?"Opera":t.indexOf("chrome")>=0?"Chrome":t.indexOf("safari")>=0?"Safari":t.indexOf("trident")>=0?"Internet Explorer":"Other",("Chrome"===e||"Safari"===e||"Opera"===e)&&"20030107"!==i)return!0;var a=eval.toString().length;if(37===a&&"Safari"!==e&&"Firefox"!==e&&"Other"!==e)return!0;if(39===a&&"Internet Explorer"!==e&&"Other"!==e)return!0;if(33===a&&"Chrome"!==e&&"Opera"!==e&&"Other"!==e)return!0;var r;try{throw"a"}catch(n){try{n.toSource(),r=!0}catch(o){r=!1}}return!(!r||"Firefox"===e||"Other"===e)},isCanvasSupported:function(){var e=document.createElement("canvas");return!(!e.getContext||!e.getContext("2d"))},isWebGlSupported:function(){if(!this.isCanvasSupported())return!1;var e,t=document.createElement("canvas");try{e=t.getContext&&(t.getContext("webgl")||t.getContext("experimental-webgl"))}catch(i){e=!1}return!!window.WebGLRenderingContext&&!!e},isIE:function(){return"Microsoft Internet Explorer"===navigator.appName||!("Netscape"!==navigator.appName||!/Trident/.test(navigator.userAgent))},hasSwfObjectLoaded:function(){return"undefined"!=typeof window.swfobject},hasMinFlashInstalled:function(){return swfobject.hasFlashPlayerVersion("9.0.0")},addFlashDivNode:function(){var e=document.createElement("div");e.setAttribute("id",this.options.swfContainerId),document.body.appendChild(e)},loadSwfAndDetectFonts:function(e){var t="___fp_swf_loaded";window[t]=function(t){e(t)};var i=this.options.swfContainerId;this.addFlashDivNode();var a={onReady:t},r={allowScriptAccess:"always",menu:"false"};swfobject.embedSWF(this.options.swfPath,i,"1","1","9.0.0",!1,a,r,{})},getWebglCanvas:function(){var e=document.createElement("canvas"),t=null;try{t=e.getContext("webgl")||e.getContext("experimental-webgl")}catch(i){}return t||(t=null),t},each:function(e,t,i){if(null!==e)if(this.nativeForEach&&e.forEach===this.nativeForEach)e.forEach(t,i);else if(e.length===+e.length){for(var a=0,r=e.length;a<r;a++)if(t.call(i,e[a],a,e)==={})return}else for(var n in e)if(e.hasOwnProperty(n)&&t.call(i,e[n],n,e)==={})return},map:function(e,t,i){var a=[];return null==e?a:this.nativeMap&&e.map===this.nativeMap?e.map(t,i):(this.each(e,function(e,r,n){a[a.length]=t.call(i,e,r,n)}),a)},x64Add:function(e,t){e=[e[0]>>>16,65535&e[0],e[1]>>>16,65535&e[1]],t=[t[0]>>>16,65535&t[0],t[1]>>>16,65535&t[1]];var i=[0,0,0,0];return i[3]+=e[3]+t[3],i[2]+=i[3]>>>16,i[3]&=65535,i[2]+=e[2]+t[2],i[1]+=i[2]>>>16,i[2]&=65535,i[1]+=e[1]+t[1],i[0]+=i[1]>>>16,i[1]&=65535,i[0]+=e[0]+t[0],i[0]&=65535,[i[0]<<16|i[1],i[2]<<16|i[3]]},x64Multiply:function(e,t){e=[e[0]>>>16,65535&e[0],e[1]>>>16,65535&e[1]],t=[t[0]>>>16,65535&t[0],t[1]>>>16,65535&t[1]];var i=[0,0,0,0];return i[3]+=e[3]*t[3],i[2]+=i[3]>>>16,i[3]&=65535,i[2]+=e[2]*t[3],i[1]+=i[2]>>>16,i[2]&=65535,i[2]+=e[3]*t[2],i[1]+=i[2]>>>16,i[2]&=65535,i[1]+=e[1]*t[3],i[0]+=i[1]>>>16,i[1]&=65535,i[1]+=e[2]*t[2],i[0]+=i[1]>>>16,i[1]&=65535,i[1]+=e[3]*t[1],i[0]+=i[1]>>>16,i[1]&=65535,i[0]+=e[0]*t[3]+e[1]*t[2]+e[2]*t[1]+e[3]*t[0],i[0]&=65535,[i[0]<<16|i[1],i[2]<<16|i[3]]},x64Rotl:function(e,t){return t%=64,32===t?[e[1],e[0]]:t<32?[e[0]<<t|e[1]>>>32-t,e[1]<<t|e[0]>>>32-t]:(t-=32,[e[1]<<t|e[0]>>>32-t,e[0]<<t|e[1]>>>32-t])},x64LeftShift:function(e,t){return t%=64,0===t?e:t<32?[e[0]<<t|e[1]>>>32-t,e[1]<<t]:[e[1]<<t-32,0]},x64Xor:function(e,t){return[e[0]^t[0],e[1]^t[1]]},x64Fmix:function(e){return e=this.x64Xor(e,[0,e[0]>>>1]),e=this.x64Multiply(e,[4283543511,3981806797]),e=this.x64Xor(e,[0,e[0]>>>1]),e=this.x64Multiply(e,[3301882366,444984403]),e=this.x64Xor(e,[0,e[0]>>>1])},x64hash128:function(e,t){
e=e||"",t=t||0;for(var i=e.length%16,a=e.length-i,r=[0,t],n=[0,t],o=[0,0],s=[0,0],l=[2277735313,289559509],h=[1291169091,658871167],u=0;u<a;u+=16)o=[255&e.charCodeAt(u+4)|(255&e.charCodeAt(u+5))<<8|(255&e.charCodeAt(u+6))<<16|(255&e.charCodeAt(u+7))<<24,255&e.charCodeAt(u)|(255&e.charCodeAt(u+1))<<8|(255&e.charCodeAt(u+2))<<16|(255&e.charCodeAt(u+3))<<24],s=[255&e.charCodeAt(u+12)|(255&e.charCodeAt(u+13))<<8|(255&e.charCodeAt(u+14))<<16|(255&e.charCodeAt(u+15))<<24,255&e.charCodeAt(u+8)|(255&e.charCodeAt(u+9))<<8|(255&e.charCodeAt(u+10))<<16|(255&e.charCodeAt(u+11))<<24],o=this.x64Multiply(o,l),o=this.x64Rotl(o,31),o=this.x64Multiply(o,h),r=this.x64Xor(r,o),r=this.x64Rotl(r,27),r=this.x64Add(r,n),r=this.x64Add(this.x64Multiply(r,[0,5]),[0,1390208809]),s=this.x64Multiply(s,h),s=this.x64Rotl(s,33),s=this.x64Multiply(s,l),n=this.x64Xor(n,s),n=this.x64Rotl(n,31),n=this.x64Add(n,r),n=this.x64Add(this.x64Multiply(n,[0,5]),[0,944331445]);switch(o=[0,0],s=[0,0],i){case 15:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+14)],48));case 14:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+13)],40));case 13:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+12)],32));case 12:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+11)],24));case 11:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+10)],16));case 10:s=this.x64Xor(s,this.x64LeftShift([0,e.charCodeAt(u+9)],8));case 9:s=this.x64Xor(s,[0,e.charCodeAt(u+8)]),s=this.x64Multiply(s,h),s=this.x64Rotl(s,33),s=this.x64Multiply(s,l),n=this.x64Xor(n,s);case 8:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+7)],56));case 7:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+6)],48));case 6:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+5)],40));case 5:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+4)],32));case 4:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+3)],24));case 3:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+2)],16));case 2:o=this.x64Xor(o,this.x64LeftShift([0,e.charCodeAt(u+1)],8));case 1:o=this.x64Xor(o,[0,e.charCodeAt(u)]),o=this.x64Multiply(o,l),o=this.x64Rotl(o,31),o=this.x64Multiply(o,h),r=this.x64Xor(r,o)}return r=this.x64Xor(r,[0,e.length]),n=this.x64Xor(n,[0,e.length]),r=this.x64Add(r,n),n=this.x64Add(n,r),r=this.x64Fmix(r),n=this.x64Fmix(n),r=this.x64Add(r,n),n=this.x64Add(n,r),("00000000"+(r[0]>>>0).toString(16)).slice(-8)+("00000000"+(r[1]>>>0).toString(16)).slice(-8)+("00000000"+(n[0]>>>0).toString(16)).slice(-8)+("00000000"+(n[1]>>>0).toString(16)).slice(-8)}},e.VERSION="1.5.1",e});


var jQueryIsLoaded = false;
try {
    if (typeof jQuery === 'undefined')
        jQueryIsLoaded = false;
    else
        jQueryIsLoaded = true;
} catch (err) {
    jQueryIsLoaded = false;
}

if (!jQueryIsLoaded) {
    //https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js
    loadScript("https://www.avis-verifies.com/review/js/jquery-1.8.2.min.js");
}

if (typeof window.avisVerifies === 'undefined') {
    window.avisVerifies = {};
}

if (typeof jQuery !== 'undefined') {
    function av_widget_click() {

        if ($('.netreviews_tab').length) { //prestashop 1.7
            var tagclassname_av = $('.netreviews_tab').attr('id');
            $("a[href='#" + tagclassname_av + "']").click();
            $('html,body').animate({
                scrollTop: $("a[href='#" + tagclassname_av + "']").offset().top
            }, 'slow');
        } else {
            $("a[href='#netreviews_reviews_tab']").click();
            $('html,body').animate({
                scrollTop: $("#netreviews_reviews_tab").offset().top
            }, 'slow');
        }

    }

    function netreviewsFilter(filter_option) {
        var current_page = parseInt($("#netreviews_rating_section").attr("data-current-page"));
        var sortbynote = parseInt($("#netreviews_rating_section").attr("data-sortbynote"));
        var current_option_filter = $("#netreviews_rating_section").attr("data-current-option");
        
        if(filter_option != 'more'){
            if(filter_option > 0){
                    sortbynote = filter_option;
                    $("#netreviews_rating_section").attr("data-sortbynote",filter_option);
                 }else{
                    sortbynote = 0;
                    $("#netreviews_rating_section").attr("data-sortbynote",0);
                    current_option_filter = $("#netreviews_reviews_filter").val();
                    $("#netreviews_rating_section").attr("data-current-option",current_option_filter);
            }
            current_page = 1;
            $("#netreviews_rating_section").attr("data-current-page",1);
        }else{
            current_page = current_page+1;
            $("#netreviews_rating_section").attr("data-current-page",current_page);
        }

        $.ajax({
            url: $("#netreviews_rating_section").attr("data-url-ajax") + "netreviews/ajax-load.php",
            type: "POST",
            data: {
                id_product: $("#netreviews_rating_section").attr("data-productid"),
                nom_group: $("#netreviews_rating_section").attr("data-group-name"),
                id_shop: $("#netreviews_rating_section").attr("data-idshop"),
                reviews_max_pages: parseInt($("#netreviews_rating_section").attr("data-max-page")),
                current_page: current_page,
                sortbynote:  sortbynote,
                current_option_filter: current_option_filter,
                filter_option: filter_option
            },
            beforeSend: function() {
                 if(filter_option != 'more'){
                    $('.loader_av').addClass("avisVerifiesAjaxImage");
                 }else{
                    $('.netreviews_button').addClass("active");
                 }
            },
            success: function(html) {
                $('.netreviews_reviews_section').replaceWith(html);
                window.avisVerifies.avLoadCookies();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('something went wrong...');
            }
        });
    }


    function manageAnimations(){
        $('#netreviews_informations_label').click(function() {
            if(!$('.netreviews_rating_header div span').hasClass('netreviews_active_info')) {
                $('.netreviews_rating_header div span').addClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeIn();
            }
            else {
                $('.netreviews_rating_header div span').removeClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeOut();
            }
        });
        $('#netreviews_informations').click(function() {
            if($('.netreviews_rating_header div span').hasClass('netreviews_active_info')) {
                $('.netreviews_rating_header div span').removeClass("netreviews_active_info");
                $('.netreviews_rating_header div span').fadeOut();
            }
        });
    }


   $(document).ready(function() {
        manageAnimations();
        avhelpfulExec = false;
        avInitialFingerPrint = '';
        avHelpfulCookie = {};
        avMessagesCookie = {};
        avHelpfulErrorMessage = $("#avHelpfulErrorMessage").val();
        avHelpfulSuccessMessage = $("#avHelpfulSuccessMessage").val();
        avHelpfulIdwebsite = $("#av_idwebsite").val();
        avHelpfulURL = $("#avHelpfulURL").val();
        voteButtons = document.getElementsByClassName("netreviewsVote");
        window.avisVerifies.avLoadCookies();
        if ($('.netreviews_tab').length) { //prestashop 1.7
            var av_tag = $("a[href='#"+$('.netreviews_tab').attr('id')+"']").text();
            if(av_tag ==""){
                 $("a[href='#"+$('.netreviews_tab').attr('id')+"']").hide();
            }
         }
    });

    function switchCommentsVisibility(review_number,type) {
        $('div[review_number=' + review_number + ']').toggle(); 
        $('a#display' + review_number + '[review_number=' + review_number + ']').toggleClass("active");
        $('a#hide' + review_number + '[review_number=' + review_number + ']').toggleClass("active");
        avMessagesCookie[review_number] = {};
        avMessagesCookie[review_number]["type"] = type;
        console.log(avMessagesCookie);
        var cookie_string_comment = "netreviews_comment=" + JSON.stringify(avMessagesCookie);
        document.cookie = cookie_string_comment;
    }
} // end jquery


window.avisVerifies.avLoadCookies = function (){
       // load netreviews_helpful
       avLoadCookie();
       avLoadCookie_comment();
       // Display existing votes
       avDisplayVotes();
       avDisplayExchangemessages();
}

// Au clic de l'internaute sur un vote
function avHelpfulClick(idProduct,vote,sign) {
    // Si double click pas d'action
    if (avhelpfulExec) { return false; }
    avhelpfulExec = true;
    // On recupère l'element <a>
    var link = document.getElementById(idProduct + '_' + vote);
    // On check si le lien est déjà actif ou non
    var linkIsActive = avHasClass(link,'active');
    // Affichage en direct de l'action
    if (!linkIsActive) {
        // Le lien n'est pas déjà actif > color
        avColorButton(idProduct,vote);
        avShowMessage(idProduct,avHelpfulSuccessMessage,'success');
    }
    else {
        // Le lien est déjà actif > uncolor
        avUnColorButtons(idProduct);
        avShowMessage(idProduct,'','');
    }
    // On calcul le fingerPrint de l'internaute
    new Fingerprint2().get(function(result, components){
        // On recharge les coockies
        avLoadCookie();
        // new vote > create
        if (!linkIsActive) {
            avCallHelpfulWebservice('create',idProduct,vote,sign,result);
        }
        // vote already sent > delete
        else {
            avCallHelpfulWebservice('delete',idProduct,vote,sign,result);
        }
    });
}
// Appel au webservice
function avCallHelpfulWebservice(method,idProduct,vote,sign,fingerPrint) {
    // Si un vote existe déjà pour cet avis on récupère le fingerPrint existant
    var existingVote = getExistingVote(idProduct);
    if (typeof existingVote.fingerPrint != "undefined" && existingVote.fingerPrint != "") {
        fingerPrint = existingVote.fingerPrint;
    }
    // Appel au webservice
    var http = new XMLHttpRequest();
    var params = "method=" + method + "&idWebsite=" + avHelpfulIdwebsite + "&idProduct=" + idProduct + "&isHelpful=" + vote + "&fingerPrint=" + fingerPrint + "&sign=" + sign;
    http.open("POST", avHelpfulURL, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            try {
              var obj = JSON.parse(http.responseText);
              if (typeof obj.success !== "undefined") {
                // SUCCESS
                if (obj.success == '1') {
                    if (obj.method == 'create') {
                        // save cookie
                        avHelpfulCookie[obj.idProduct] = {};
                        avHelpfulCookie[obj.idProduct]["vote"] = obj.isHelpful;
                        avHelpfulCookie[obj.idProduct]["fingerPrint"] = obj.fingerPrint;
                        avSaveCookie();
                    }
                    if (obj.method == 'delete') {
                        // remove cookie
                        if (typeof avHelpfulCookie[obj.idProduct] !== "undefined") {
                            delete avHelpfulCookie[obj.idProduct];
                            avSaveCookie();
                        }
                    }
                }
                // ERROR
                if (obj.success == '0') {
                    avUnColorButtons(obj.idProduct);
                    avShowMessage(obj.idProduct,avHelpfulErrorMessage,'error');
                    console.log('[NetReviews] Error ' + obj.errorCode + ' : ' + obj.errorMessage);
                }
              }
            } catch (e) {
                console.error("Parsing error:", e); 
                avUnColorButtons(idProduct);
                avShowMessage(idProduct,avHelpfulErrorMessage,'error');
                console.log('[NetReviews] Unknown error.');
            }
        }
        avhelpfulExec = false;
    }
    http.send(params);
}
// Met en avant le vote de l'internaute
function avColorButton(idProduct,isHelpful) {
    var link = document.getElementById(idProduct + '_' + isHelpful);
    var linkIsActive = avHasClass(link,'active');
    if (!linkIsActive) {
        link.classList.add("active");
    }
    if (isHelpful=='0') {
        var otherLink = document.getElementById(idProduct + '_1')
    }
    else {
        var otherLink = document.getElementById(idProduct + '_0')
    }
    otherLink.classList.remove("active");
}
// Masque le vote de l'internaute
function avUnColorButtons(idProduct) {
    var link_yes = document.getElementById(idProduct + '_1');
    var link_no  = document.getElementById(idProduct + '_0');
    link_yes.classList.remove("active");
    link_no.classList.remove("active");
}
// Affiche un message de confirmation ou d'erreur
function avShowMessage(idProduct,message,type) {
    var p = document.getElementById(idProduct + '_msg');
    if (typeof p !== "undefined" && p != "null") {
        p.innerHTML = message;
        if (message != "") {p.style.display = 'block';}
        if (message == "") {p.style.display = 'none';}
        if (type == 'success') { p.style.color = '#0c9c5b'; }
        if (type == 'error') { p.style.color = '#bf2525'; }
    }
}
// Test si un element possède une classe css
function avHasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}
// Charge le cookie netreviews_helpful
function avLoadCookie() {
    var name = "netreviews_helpful=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    avHelpfulCookie = {};
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            avHelpfulCookie = JSON.parse(c.substring(name.length, c.length));
        }
    }
}

function avLoadCookie_comment() {
    var name = "netreviews_comment=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    avMessagesCookie = {};
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            avMessagesCookie = JSON.parse(c.substring(name.length, c.length));
        }
    }
}
// Sauvegarde le cookie netreviews_helpful
function avSaveCookie() {
    var expiration_date = new Date();
    expiration_date.setFullYear(expiration_date.getFullYear() + 1);
    var cookie_value = JSON.stringify(avHelpfulCookie);
    var cookie_string = "netreviews_helpful=" + cookie_value + "; path=/; expires=" + expiration_date.toUTCString();
    document.cookie = cookie_string;
}
// Affiche les votes existants
function avDisplayVotes () {
    var voteButtons = document.getElementsByClassName("netreviewsVote");
    // console.log(voteButtons);
    for (var i = 0; i < voteButtons.length; i++) {
        var idProduct = voteButtons[i].getAttribute("data-review-id");
        if (typeof idProduct !== "undefined" && idProduct != "") {
            var existingVote = getExistingVote(idProduct);
            if (typeof existingVote.vote != "undefined") {
                avColorButton(idProduct,existingVote.vote);
            }
        }
    }
}

function avDisplayExchangemessages () {
    var commentstatus = document.getElementsByClassName("netreviews_button_comment");
    // // console.log(commentstatus);
    for (var i = 0; i < commentstatus.length; i++) {
        var reviewnumber = commentstatus[i].getAttribute("review_number");
        if (typeof reviewnumber !== "undefined" && reviewnumber && avMessagesCookie[reviewnumber]!== "undefined" && avMessagesCookie[reviewnumber]) {
                if (avMessagesCookie[reviewnumber]["type"] == 1){
                    $('a#display' + reviewnumber).removeClass("active");
                    $('a#hide' + reviewnumber).addClass("active");
                    $('div[review_number=' + reviewnumber + ']').show(); 
                }
        }
    }
}

// Affiche les votes existants
function getExistingVote (idProduct) {
    if (typeof avHelpfulCookie[idProduct] !== "undefined")
        return avHelpfulCookie[idProduct];
    else 
        return {};
}

function loadScript(url, callback) {
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState) { //IE
        script.onreadystatechange = function() {
            if (script.readyState == "loaded" || script.readyState == "complete") {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else { //Others
        script.onload = function() {
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}

/*jslint devel: true */

(function () {

    "use strict";

    /**
     * Adding an event listener on every thumbnail for the modal to open and adding keyboard event binding
     * @function _init
    //  */
    (function _init() {
        addListeners();
        document.addEventListener('keydown', nrKeyboardEvent);
    })();

    /**
     * Function handling the add of event listeners on each thumbnail
     * @function addListeners
     */
    function addListeners() {
        document.addEventListener('click', function (event) {
            if (event.target.className === 'netreviews_image_thumb') {
                var targetElement = event.target || event.srcElement;
                openModal(targetElement);
            }
        });
    }

    /**
     * Carousel constructor
     *
     * @param {object} parent Parent div containing an <ul> list that is going to be used for carousel setup
     * @param {number} currentItem Clicked item number
     * @param {number} count Number of children in the list (<li>)
     * @constructor
     */
    function Carousel(parent, currentItem, count) {
        var vm = this;
        vm.element = parent;
        vm.current = currentItem;
        vm.count = count;
        vm.arrPrev = document.createTextNode('‹');
        vm.arrNext = document.createTextNode('›');
        vm.cPrevClass = 'carousel-prev';
        vm.cNextClass = 'carousel-next';
        vm.arrowClass = 'carousel-arrow';
        vm.crslClass = 'netreviews_media_part';
        vm.showPrev = showPrev;
        vm.showNext = showNext;
        vm.showArrows = showArrows;

        /**
         * Go to previous image
         * @function showPrev
         */
        function showPrev() {
            if (vm.current === 0) {
                vm.current = vm.count - 1;
            }
            else {
                vm.current = vm.current - 1;
            }
            updateModal(vm.element.querySelectorAll('.' + vm.crslClass + ' > li > a')[vm.current]);
        }

        /**
         * Go to next image
         * @function showNext
         */
        function showNext() {
            if (vm.current === vm.count - 1) {
                vm.current = 0;
            }
            else {
                vm.current = vm.current + 1;
            }
            updateModal(vm.element.querySelectorAll('.' + vm.crslClass + ' > li > a')[vm.current]);
        }

        /**
         * Create the navigation arrows (prev/next) and attach to carousel.
         * @function showArrows
         */
        function showArrows() {
            var modal = document.getElementById('netreviews_media_modal');

            var buttonPrev = document.createElement('a');
            buttonPrev.appendChild(vm.arrPrev);
            buttonPrev.classList.add(vm.cPrevClass);
            buttonPrev.classList.add(vm.arrowClass);
            buttonPrev.id = 'netreviews_media_prev';

            var buttonNext = document.createElement('a');
            buttonNext.appendChild(vm.arrNext);
            buttonNext.classList.add(vm.cNextClass);
            buttonNext.classList.add(vm.arrowClass);
            buttonNext.id = 'netreviews_media_next';

            // Adding event listener on arrow click
            buttonPrev.addEventListener('click', vm.showPrev);
            buttonNext.addEventListener('click', vm.showNext);

            // Adapting buttons positions
            var browserheight = document.documentElement.clientHeight;
            buttonPrev.style.top = ((browserheight - 75) / 2) + 'px';
            buttonNext.style.top = ((browserheight - 75) / 2) + 'px';

            modal.appendChild(buttonPrev);
            modal.appendChild(buttonNext);
        }
    }

    /**
     * Get current media index on click
     * @function indexInParent
     *
     * @param node
     * @returns {number} Media number
     */
    function indexInParent(node) {
        var children = node.parentNode.childNodes;
        var num = 0;
        for (var i = 0; i < children.length; i++) {
            if (children[i] === node) {
                return num;
            }
            if (children[i].nodeType === 1) {
                num++;
            }
        }
    }

    /**
     * Catch keyboard keydown : next / prev
     * @function nrKeyboardEvent
     */
    function nrKeyboardEvent(e) {
        e = e || window.event;
        var buttonPrev = document.getElementById('netreviews_media_prev');
        var buttonNext = document.getElementById('netreviews_media_next');
        var buttonClose = document.getElementById('netreviews_media_close');
        switch (e.keyCode) {
            case 37:
                if (typeof buttonPrev !== 'undefined' && buttonPrev !== null) {
                    buttonPrev.click();
                }
                break;
            case 39:
                if (typeof buttonNext !== 'undefined' && buttonNext !== null) {
                    buttonNext.click();
                }
                break;
            case 27:
                if (typeof buttonClose !== 'undefined' && buttonClose !== null) {
                    buttonClose.click();
                }
                break;
        }
    }

    /**
     * Open modal container
     * @function openModal
     */
    function openModal(event) {
        var identifier = event;
        var closeButton = document.getElementById("netreviews_media_close");
        var dataType = identifier.getAttribute('data-type');
        var dataSrc = identifier.getAttribute('data-src');
        var modal = document.getElementById('netreviews_media_modal');
        var parent = identifier.parentNode.parentNode;
        var count = identifier.parentNode.parentNode.querySelectorAll('li').length;
        var current = indexInParent(identifier.parentNode);
        var loader = document.createElement('div');

        loader.id = 'loader';
        loader.className = 'loader-image';
        modal.appendChild(loader);

        // Render the carousel with focus on current clicked element
        if (count > 1) {
            var carousel = new Carousel(parent, current, count);
            carousel.showArrows();
        }

        closeButton.addEventListener("click", closeModal);

        fillModal(modal, dataType, dataSrc);
    }

    /**
     * Fill modal with media info
     * @function fillModal
     *
     * @param modal
     * @param dataType
     * @param dataSrc
     */
    function fillModal(modal, dataType, dataSrc) {
        var modalContent = document.getElementById('netreviews_media_content');
        // Image display
        if (dataType === 'image') {
            var newImg = new Image();
            newImg.onload = function () {
                modalContent.innerHTML = "<img id='netreviews_media_image' src='" + dataSrc + "'/>";
                resizeMedia('netreviews_media_image', newImg.height, newImg.width);
                modal.style.display = 'block';
            };
            newImg.src = dataSrc;
        }
        // Video display

        else if (dataType === 'video') {
            modalContent.innerHTML = "<iframe id='netreviews_media_video' src='" + dataSrc + "'/>";
            resizeMedia('netreviews_media_video', '500', '800');
            modal.style.display = 'block';
        }
    }

    /**
     * On prev/next arrow click, update modal with media info
     * @function updateModal
     *
     * @param {Object} item An <a> tag containing source and type attribute
     */
    function updateModal(item) {
        var dataSrc = item.getAttribute('data-src');
        var dataType = item.getAttribute('data-type');
        var modal = document.getElementById('netreviews_media_modal');

        fillModal(modal, dataType, dataSrc);
    }

    /**
     * Close modal
     * @function closeModal
     */
    function closeModal() {
        var iframeNetreviews = document.getElementById('netreviews_media_iframe');
        var modal = document.getElementById('netreviews_media_modal');
        var modalContent = document.getElementById('netreviews_media_content');

        // Empty modal
        modalContent.innerHTML = '';
        removeArrows();
        removeLoader();

        modal.style.display = 'none';
        if (iframeNetreviews) {
            iframeNetreviews.setAttribute('src', '');
        }
    }

    /**
     * Remove arrows from carousel on modal close
     * @function removeArrows
     */
    function removeArrows() {
        var arrows = document.getElementsByClassName('carousel-arrow');
        var modal = document.getElementById('netreviews_media_modal');
        var arrayFromArrows = [];

        for (var j = 0; j < arrows.length; j++) {
            arrayFromArrows.push(arrows[j]);
        }

        arrayFromArrows.forEach(function (element) {
            modal.removeChild(element);
        });
    }

    /**
     * Remove loader on modal close
     * @function removeLoader
     */
    function removeLoader() {
        var modal = document.getElementById('netreviews_media_modal');
        var loader = document.getElementById('loader');
        modal.removeChild(loader);
    }

    /**
     * Resize media to fit container
     * @function resizeMedia
     *
     * @param {Object} blocId
     * @param {number} initialImgHeight
     * @param {number} initialImgWidth
     */
    function resizeMedia(blocId, initialImgHeight, initialImgWidth) {
        var desiredWidth;
        var desiredHeight;
        //display ratio
        var displayRatio = 0.8;
        //define image ratio
        var ratio = initialImgHeight / initialImgWidth;
        //get browser dimensions
        var browserwidth = document.documentElement.clientWidth;
        var browserheight = document.documentElement.clientHeight;
        //image plus large que haute
        if (initialImgWidth > initialImgHeight) {
            // Dimensions souhaitées
            desiredWidth = browserwidth * displayRatio;
            desiredHeight = (browserwidth * ratio) * displayRatio;
            // Si hauteur plus grande que l'ecran on adapte par la hauteur
            if (desiredHeight > browserheight) {
                desiredHeight = browserheight * displayRatio;
                desiredWidth = (browserheight / ratio) * displayRatio;
            }
        }
        //image plus haute que large
        else {
            // Dimensions souhaitées
            desiredHeight = browserheight * displayRatio;
            desiredWidth = (browserheight / ratio) * displayRatio;
            // Si largeur plus grande que l'ecran on adapte par la largeur
            if (desiredWidth > browserwidth) {
                desiredWidth = browserwidth * displayRatio;
                desiredHeight = (browserwidth * ratio) * displayRatio;
            }
        }
        // La taille maximum d'affichage est la taille réelle de l'image
        // Ne jamais zoomer une image
        if (initialImgWidth < desiredWidth && initialImgHeight < desiredHeight) {
            desiredWidth = initialImgWidth;
            desiredHeight = initialImgHeight;
        }
        // On redimensionne l'image
        document.getElementById(blocId).style.width = desiredWidth + 'px';
        document.getElementById(blocId).style.height = desiredHeight + 'px';
        // On update la position de l'image
        document.getElementById(blocId).style.left = ((browserwidth - desiredWidth) / 2) + 'px';
        document.getElementById(blocId).style.top = ((browserheight - desiredHeight) / 2) + 'px';
    }
})();