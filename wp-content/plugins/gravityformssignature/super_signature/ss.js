ValidateSignature=function(a){var b=true;if(a==null||'undefined'==a||""==a){for(var i=0;i<signObjects.length;i++){var c=signObjects[i],isitOk=eval("obj"+c).IsValid();if(!isitOk){b=false;if(!isMobileIE){document.getElementById(c).style.borderColor="red"}}}}else{b=eval("obj"+a).IsValid();if(!isMobileIE&&b==false){document.getElementById(a).style.borderColor="red"}}return b};ClearSignature=function(a){if(a==null||'undefined'==a||""==a){for(var i=0;i<signObjects.length;i++){var a=signObjects[i];eval("obj"+a).ResetClick()}}else{eval("obj"+a).ResetClick()}};ResizeSignature=function(a,w,h){eval("obj"+a).ResizeSignature(w,h)};SignatureColor=function(a,b){eval("obj"+a).SignatureColor(b)};SignatureBackColor=function(a,b){eval("obj"+a).SignatureBackColor(b)};SignaturePen=function(a,b){eval("obj"+a).SignaturePen(b)};SignatureEnabled=function(a,b){eval("obj"+a).SignatureEnabled(b)};SignatureStatusBar=function(a,b){eval("obj"+a).SignatureStatusBar(b)};SignatureTotalPoints=function(a){return eval("obj"+a).CurrentTotalpoints()};UndoSignature=function(a){eval("obj"+a).UndoSignature()};LoadSignature=function(a,b){eval("obj"+a).LoadSignature(b)};var msie=window.navigator.userAgent.toUpperCase().indexOf("MSIE ");var isIE=false;var isIENine=false;var isMobileIE=false;var isOperaMini=false;if(window.navigator.userAgent.toUpperCase().indexOf("OPERA MINI")>0){isOperaMini=true}if(window.navigator.userAgent.toUpperCase().indexOf("OPERA MOBI")>0){isOperaMini=true}function supports_canvas(){try{document.createElement("canvas").getContext("2d");return true}catch(e){return false}};function getInternetExplorerVersion(){var a=-1;if(window.navigator.appName=='Microsoft Internet Explorer'){var b=window.navigator.userAgent;var c=new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");if(c.exec(b)!=null)a=parseFloat(RegExp.$1)}return a};if(msie>0){isIE=true;if(supports_canvas()){isIE=false;var ver=getInternetExplorerVersion();if(ver>=9.0){isIENine=true}}if(window.navigator.userAgent.toUpperCase().indexOf("IEMOBILE ")>0){isMobileIE=true}if(window.navigator.userAgent.toUpperCase().indexOf("WINDOWS PHONE ")>0){isMobileIE=true}}function SuperSignature(){this.SignObject="";this.PenSize=3;this.PenColor="#D24747";this.PenCursor='';this.ClearImage='';this.BorderWidth="2px";this.BorderStyle="dashed";this.BorderColor="#DCDCDC";this.BackColor="#ffffff";this.BackImageUrl='';this.SignzIndex="99";this.SignWidth=450;this.SignHeight=250;this.CssClass="";this.ApplyStyle=true;this.SignToolbarBgColor="transparent";this.RequiredPoints=50;this.ErrorMessage="Please continue your signature.";this.StartMessage="Please sign";this.SuccessMessage="Signature OK.";this.ImageScaleFactor=0.50;this.TransparentSign=true;this.Enabled=true;this.Visible=true;this.Edition="Trial";this.IsCompatible=false;this.InternalError="";this.LicenseKey="";this.IeModalFix=false;this.SuccessFunction="";this.ErrorFunction="";this.ResetFunction="";for(var n in arguments[0]){this[n]=arguments[0][n]}var q=0;var r=false;var s=null;var u=null;var x=null;var y=null;var z=null;var A=null;var B=this.Enabled;var C=false;var D=[],fData=[],kData=[];var E="1",dcMode=false,currPenSize=this.PenSize,currPenColor=this.PenColor,currBackColor=this.BackColor,currBorderColor=this.BorderColor;var F=this.SignObject;var G=this.SignWidth;var H=this.SignHeight;var I=this.ErrorMessage;var J=this.SuccessMessage;var K=this.SuccessFunction;var L=this.ErrorFunction;var M=this.ResetFunction;var N=this.BackImageUrl;var O=false;var P=this.ImageScaleFactor;var Q=this.TransparentSign;var a="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var R=this.RequiredPoints;var S="";var T=0;var U=0;var V;var W=0;var X=0;var Y=this.IeModalFix;var Z=null;var ba=0;var bb=0;if(isMobileIE){V=new jsGraphics(F+"_Container");if(V!=null&&V!='undefined'){try{V.clear();V.paint()}catch(ee){alert("Graphics object error "+ee.description)}}else{alert("Graphics object error")}};this.IsValid=function(){return O};this.CurrentTotalpoints=function(){return currTotalPts};this.ReturnFalse=function(e){if(!isIE){e.preventDefault()}return false};function MyAttachEvent(a,b,c){if(!a.myEvents)a.myEvents={};if(!a.myEvents[b])a.myEvents[b]=[];var d=a.myEvents[b];d[d.length]=c};function MyFireEvent(a,b){if(!a||!a.myEvents||!a.myEvents[b])return;var c=a.myEvents[b];for(var i=0,len=c.length;i<len;i++)c[i]()};this.XBrowserAddHandler=function(a,b,c){if(a.addEventListener)a.addEventListener(b,c,false);else if(a.attachEvent)a.attachEvent("on"+b,c);else{try{MyAttachEvent(a,b,c);a['on'+b]=function(){MyFireEvent(a,b)}}catch(ex){alert("Event attaching exception, "+ex.description)}}};this.DisableSelection=function(){if(!isIE){if(typeof u.style.MozUserSelect!="undefined"){u.style.MozUserSelect="none"}else{u.style.cursor="default"}}};this.ResizeSignature=function(w,h){u.style.width=w+"px";u.style.height=h+"px";y.style.width=w+"px";if(!isIE){var a=document.getElementById(this.SignObject);a.width=parseInt(w,0);a.height=parseInt(h,0);a.style.width=w+"px";a.style.height=h+"px"}else{s.style.width=w+"px";s.style.height=h+"px"}this.ResetClick();this.SignWidth=w;this.SignHeight=h;G=w;H=h};this.SignatureColor=function(a){this.PenColor=a;currPenColor=a};this.SignatureBackColor=function(a){this.BackColor=a;currBackColor=a;if(isIE){s.style.backgroundColor=a}else{s.fillStyle=a;s.fillRect(0,0,G,H)}};this.SignaturePen=function(a){this.PenSize=a;currPenSize=a};this.SignatureEnabled=function(a){this.Enabled=a;B=a};this.SignatureStatusBar=function(a){if(a){jQuery("#"+y.id).show("slow")}else{jQuery("#"+y.id).hide("slow")}};this.UndoSignature=function(){if(D.length<=2){this.ResetClick();return}D.pop();kData.pop();SetSignData();var a=base64Decode("'"+z.value+"'");this.LoadSignature(a)};this.LoadSignature=function(a,b){this.ResetClick();if(b==null||b=='undefined'){b='1.0'}b=parseFloat(b);var c=findPos(u);var d=c[0];var e=c[1];var f=RTrim(LTrim(a)).split(";");D[0]="";for(var i=0;i<f.length-1;i++){D[i]=f[i]+";"}var h=f[0].split(",");currBackColor=h[1];G=h[3];H=h[4];Q=h[5];F=h[7];this.SignatureBackColor(currBackColor);kData[0]=0;for(var i=1,len=f.length;i<len-1;i++){var k=RTrim(LTrim(f[i])).split(" ");kData[i]=parseInt(k.length,0)-1;kData[0]=parseInt(kData[0],0)+parseInt(k.length,0);for(var j=0,lent=k.length;j<lent;j++){var l=k[j].split(",");var m=l[0];var n=l[1];if(j==0){this.SignaturePen(m);this.SignatureColor(n);s.strokeStyle=n;s.lineWidth=m}else if(j==1){m=Math.abs(parseInt(m,0)*b);n=Math.abs(parseInt(n,0)*b);if(isIE){if(isMobileIE){T=m;U=n}else{var w='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />';var t='"m'+m+","+n+" l"+m+","+n;var v='<SuperSignature:shape id="'+F+"_l_"+(i-1)+'" style="position: absolute; left:0px; top:0px; width:'+G+"px; height: "+H+'px;" coordsize="'+G+","+H+'"><SuperSignature:path v='+t+' e" /><SuperSignature:fill on="false" />'+w+"</SuperSignature:shape>";s.pathCoordString=t;s.insertAdjacentHTML("beforeEnd",v)}}else{s.beginPath();s.lineJoin="round";s.lineCap="round";s.moveTo(m,n)}if(k.length==2){eval("obj"+F).DrawDot(m,n)}}else{m=Math.abs(parseInt(m,0)*b);n=Math.abs(parseInt(n,0)*b);if(!isIE){s.strokeStyle=currPenColor;s.lineWidth=currPenSize;s.lineTo(m,n);s.stroke();s.moveTo(m,n)}else{s.pathCoordString+=" "+m+","+n;var g=document.getElementById(F+"_l_"+(i-1));if(g){var o=g.firstChild;if(o){try{o.setAttribute("v",s.pathCoordString+" e")}catch(je){var p=je.Description}}}}}if(!isIE){s.closePath();s.restore()}else{s.innerHTML=s.innerHTML}}q++}SetSignData()};this.CheckCompatibility=function(){if(isIE){this.IsCompatible=true;if(!isMobileIE){if(!document.namespaces["SuperSignature"]){document.namespaces.add("SuperSignature","urn:schemas-microsoft-com:vml","#default#VML")}}}else{var a=false;try{a=!!document.createElement("canvas").getContext("2d")}catch(e){a=!!document.createElement("canvas").getContext}if(a){this.IsCompatible=true}else{document.write("Your browser does not support our signature control.")}}};function ShowMessage(a){ShowDebug(a)};function LTrim(a){var b=/\s*((\S+\s*)*)/;return a.replace(b,"$1")};function RTrim(a){var b=/((\s*\S+)*)\s*/;return a.replace(b,"$1")};function ShowDebug(a){if(A!=null&&A!='undefined'){try{A.innerHTML=a+"...<br/>"+A.innerHTML}catch(exp1){alert(exp1.description)}}};function base64Encode(b){var h="",i,d,e,k,l,j,f,g=0;b=c(b);while(g<b.length){i=b.charCodeAt(g++);d=b.charCodeAt(g++);e=b.charCodeAt(g++);k=i>>2;l=(i&3)<<4|d>>4;j=(d&15)<<2|e>>6;f=e&63;if(isNaN(d)){j=f=64}else if(isNaN(e)){f=64}h=h+a.charAt(k)+a.charAt(l)+a.charAt(j)+a.charAt(f)}return h};function b(c){var d="",a=0,b=c1=c2=0;while(a<c.length){b=c.charCodeAt(a);if(b<128){d+=String.fromCharCode(b);a++}else if(b>191&&b<224){c2=c.charCodeAt(a+1);d+=String.fromCharCode((b&31)<<6|c2&63);a+=2}else{c2=c.charCodeAt(a+1);c3=c.charCodeAt(a+2);d+=String.fromCharCode((b&15)<<12|(c2&63)<<6|c3&63);a+=3}}return d};function c(c){c=c.replace(/\x0d\x0a/g,"\n");for(var b="",d=0;d<c.length;d++){var a=c.charCodeAt(d);if(a<128){b+=String.fromCharCode(a)}else if(a>127&&a<2048){b+=String.fromCharCode(a>>6|192);b+=String.fromCharCode(a&63|128)}else{b+=String.fromCharCode(a>>12|224);b+=String.fromCharCode(a>>6&63|128);b+=String.fromCharCode(a&63|128)}}return b};function base64Decode(b){var c="";var d,chr2,chr3="";var e,enc2,enc3,enc4="";var i=0;var f=a;var g=/[^A-Za-z0-9\+\/\=]/g;if(g.exec(b)){}b=b.replace(/[^A-Za-z0-9\+\/\=]/g,"");do{e=f.indexOf(b.charAt(i++));enc2=f.indexOf(b.charAt(i++));enc3=f.indexOf(b.charAt(i++));enc4=f.indexOf(b.charAt(i++));d=(e<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;c=c+String.fromCharCode(d);if(enc3!=64){c=c+String.fromCharCode(chr2)}if(enc4!=64){c=c+String.fromCharCode(chr3)}d=chr2=chr3="";e=enc2=enc3=enc4=""}while(i<b.length);return unescape(c)};function SetSignData(){kData[0]=0;for(var h=1;h<kData.length;h++){kData[0]+=kData[h]}O=kData[0]>=R?true:false;currTotalPts=kData[0];var j="";D[0]=E+","+currBackColor+","+P+","+G+","+H+","+Q+","+kData[0]+","+F+";";for(var p=0;p<D.length;p++){j+=D[p]}if(D.length>1){z.value=base64Encode(j)}else{z.value=""}};function findPos(a){var b=curtop=0;if(a.offsetParent){do{b+=a.offsetLeft;curtop+=a.offsetTop}while(a=a.offsetParent)}return[b,curtop]}this.MouseMove=function(e){if(!B){return false}if(!C){return false}if(!isIE){e.preventDefault()}var a=0,ptY=0;if(r){var b=e.targetTouches[0];a=b.pageX-W;ptY=b.pageY-X}else if(isIE||isIENine){a=e.x;ptY=e.y}else{var c=jQuery('#'+u.id).offset();a=e.pageX-c.left;ptY=e.pageY-c.top}ShowDebug("("+a+","+ptY+")");if(isMobileIE){fData.push(Math.abs(parseInt(a)-parseInt(u.offsetLeft))+","+Math.abs(parseInt(ptY)-parseInt(u.offsetTop)))}else{fData.push(Math.ceil(parseInt(a))+","+Math.ceil(parseInt(ptY)))}kData[q]++;if(!isIE){s.lineTo(a,ptY);s.stroke()}else{if(isMobileIE){var d=(a-T);var f=(ptY-U);var h=(d*d+f*f);var k=(8*8);if(h>k){if(V!=null&&V!='undefined'){try{V.setStroke(currPenSize);V.setColor(currPenColor);V.drawLine(T,U,a,ptY);V.paint()}catch(mme){alert("Drawing error: "+mme.description)}}else{ShowDebug("Graphics object NULL")}T=a;U=ptY}}else{s.pathCoordString+=" "+a+","+ptY;var g=document.getElementById(F+"_l_"+q);if(g){var i=g.firstChild;if(i){try{i.setAttribute("v",s.pathCoordString+" e")}catch(j){}if(dcMode&&kData[q]%8==0){s.innerHTML=s.innerHTML}}}}}};this.DrawDot=function(a,b){kData[q]++;if(!isIE){s.arc(a,b,currPenSize/2,0,2*Math.PI,false);s.fill();s.stroke()}else{var c='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />';var d='<SuperSignature:oval id="'+F+"_l_"+q+'" style="position: absolute; left:'+a+'px; top:'+b+'px; width:'+currPenSize+"px; height: "+currPenSize+'px;"'+'">'+c+"</SuperSignature:oval>";s.insertAdjacentHTML("beforeEnd",d)}};this.MouseOut=function(e){if(!B){return}ShowDebug("Mouse out");C=false;if(!isIE){s.closePath();s.restore()}else{s.innerHTML=s.innerHTML}};this.MouseUp=function(e){if(!B){return}ShowDebug("Mouse up");C=false;if(null!=Z){var a=parseInt(new Date()-Z);if(a<125){var b=0,ptY=0;ShowDebug("Time diff "+a);if(r){b=ba;ptY=bb}else{if(isIE||isIENine){b=e.x;ptY=e.y}else{var c=jQuery('#'+u.id).offset();b=e.pageX-c.left;ptY=e.pageY-c.top}}ShowDebug("Drawing Dot At ("+b+","+ptY+")");eval("obj"+F).DrawDot(b,ptY)}Z=null}if(fData.length>0){D[q]=" "+fData.join(" ")+";"}SetSignData();if(kData[0]<R){x.innerHTML=I}else{x.innerHTML=J}if(!isIE){s.closePath();s.restore()}else{s.innerHTML=s.innerHTML}if(r){W=0;X=0}};this.MouseDown=function(e){if(!B){return false}if(!isIE){e.preventDefault()}ShowDebug("Mouse down");Z=new Date();C=true;var a,ptY;if(r){var b=e.targetTouches[0];if(W==0){var c=findPos(u);W=c[0];X=c[1]}a=b.pageX-W;ptY=b.pageY-X;ba=a;bb=ptY}else{var d=jQuery('#'+u.id).offset();if(isIE||isIENine){a=parseInt(e.x);ptY=parseInt(e.y);if(Y){if(isIENine){a=parseInt(e.pageX)-parseInt(d.left)+parseInt(jQuery('html').css('margin-left'));ptY=parseInt(e.pageY)-parseInt(d.top)+parseInt(jQuery('html').css('margin-top'))}}}else{a=e.pageX-parseInt(d.left);ptY=e.pageY-parseInt(d.top)}}ShowDebug("Down ("+a+","+ptY+")");q++;kData[q]=0;fData.length=0;fData[0]=currPenSize+","+currPenColor;if(isMobileIE){fData.push(Math.abs(parseInt(a)-parseInt(u.offsetLeft))+","+Math.abs(parseInt(ptY)-parseInt(u.offsetTop)))}else{fData.push(Math.ceil(parseInt(a))+","+Math.ceil(parseInt(ptY)))}if(isIE){if(isMobileIE){T=a;U=ptY}else{var w='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />',t='"m'+a+","+ptY+" l"+a+","+ptY,v;v='<SuperSignature:shape id="'+F+"_l_"+q+'" style="position: absolute; left:0px; top:0px; width:'+G+"px; height: "+H+'px;" coordsize="'+G+","+H+'"><SuperSignature:path v='+t+' e" /><SuperSignature:fill on="false" />'+w+"</SuperSignature:shape>";s.pathCoordString=t;s.insertAdjacentHTML("beforeEnd",v)}}else{s.save();s.beginPath();s.lineJoin="round";s.lineCap="round";s.strokeStyle=currPenColor;s.lineWidth=currPenSize;s.moveTo(a,ptY)}return false};this.ResetClick=function(e){if(!B){return}if(!isMobileIE){document.getElementById(F).style.borderColor=currBorderColor}if(isIE){s.innerHTML="";if(isMobileIE){T=0;U=0;if(V!=null&&V!='undefined'){V.clear();V.paint()}}}else{s.clearRect(0,0,G,H);if(N.length>0){}else{SignatureBackColor(F,currBackColor)}}D=[],fData=[],kData=[];SetSignData();q=0;S="";x.innerHTML="";if(M.length>0){eval(M+'();')}};this.Init=function(){this.CheckCompatibility();if(this.IsCompatible){A=document.getElementById(this.SignObject+"_Debug");s=document.getElementById(this.SignObject);u=document.getElementById(this.SignObject+"_Container");if(s.addEventListener){ShowDebug("addEventListener supported")}else if(s.attachEvent){ShowDebug("attachEvent supported")}else{ShowDebug("Mouse events are not supported");return}this.enabled=this.Enabled;this.mouseMoved=false;if(s!=null&&s!='undefined'){ShowDebug("Objects OK");if(isIE&&!isMobileIE){dcMode=document.documentMode?document.documentMode>=8:false}if(isMobileIE){ShowDebug("Mobile IE")}if(isOperaMini){ShowDebug("Opera Mini")}if(!this.Visible){ShowDebug("Control hidden");s.style.display="none";return}kData[0]=0;D[0]=E+","+currBackColor+","+P+","+G+","+H+","+Q+","+kData[0]+","+F+";";if(this.ApplyStyle){ShowDebug("Setting up style");try{if(isMobileIE){u.style.borderWidth=this.BorderWidth;u.style.borderStyle=this.BorderStyle;u.style.borderColor=this.BorderColor;u.style.backgroundColor=this.BackColor;u.style.zIndex=this.SignzIndex;u.style.cursor="url('"+this.PenCursor+"'), pointer";if(this.BackImageUrl.length>0){u.style.backgroundImage='url("'+this.BackImageUrl+'")'}}else{s.style.borderWidth=this.BorderWidth;s.style.borderStyle=this.BorderStyle;s.style.borderColor=this.BorderColor;s.style.backgroundColor=this.BackColor;s.style.zIndex=this.SignzIndex;s.style.cursor="url('"+this.PenCursor+"'), pointer";if(this.BackImageUrl.length>0){s.style.backgroundImage='url("'+this.BackImageUrl+'")'}if(this.CssClass!=""){s.className=this.CssClass}s.style.width=this.SignWidth+"px";s.style.height=this.SignHeight+"px";s.style.position="absolute";s.style.left="0px";s.style.top="0px";u.style.position="relative";if(s.style.cursor=="auto"){s.style.cursor="url('"+this.PenCursor+"'), hand"}}ShowDebug("Style OK")}catch(exs){ShowDebug("Style Error : "+exs.description)}}var a='<div id="'+this.SignObject+'_toolbar" style="margin:5px;position:relative;height:20px;width:'+this.SignWidth+'px;background-color:'+this.SignToolbarBgColor+';"><img  id="'+this.SignObject+'_resetbutton" src="'+this.ClearImage+'" style="cursor:pointer;float:right;height:24px;width:24px;border:0px solid transparent" alt="Clear Signature" />';a+='<div id="'+this.SignObject+'_status" style="color:blue;height:20px;width:auto;padding:2px;font-family:verdana;font-size:12px;float:left;margin-right:30px;">'+this.StartMessage+"</div>";a+=document.getElementById(this.SignObject+"_data")==null?'<input type="hidden" id="'+this.SignObject+'_data" name="'+this.SignObject+'_data" value="">':"";a+="</div>";ShowDebug("Setting up tools");jQuery("#"+u.id).after(a);ShowDebug("Attaching mouse out");if(isIE){this.XBrowserAddHandler(s,"mouseleave",this.MouseOut)}else{this.XBrowserAddHandler(s,"mouseout",this.MouseOut)}q=0;var b="mousedown",mouseUpEvent="mouseup",mouseMoveEvent="mousemove";r=(new RegExp("iPhone","i")).test(navigator.userAgent)||(new RegExp("iPad","i")).test(navigator.userAgent)||(new RegExp("Android","i")).test(navigator.userAgent)||(new RegExp("playbook","i")).test(navigator.userAgent)||(new RegExp("symbian","i")).test(navigator.userAgent);if(r){ShowDebug("Found iPhone");b="touchstart";mouseUpEvent="touchend";mouseMoveEvent="touchmove"}if(!isIE){s=document.getElementById(this.SignObject).getContext("2d");s.width=this.SignWidth;s.height=this.SignHeight}x=document.getElementById(this.SignObject+"_status");y=document.getElementById(this.SignObject+"_toolbar");z=document.getElementById(this.SignObject+"_data");var c=document.getElementById(this.SignObject+"_resetbutton");if(null!=c){this.XBrowserAddHandler(c,"click",this.ResetClick)}ShowDebug("Attaching events");this.XBrowserAddHandler(u,"contextmenu",this.ReturnFalse);this.XBrowserAddHandler(u,"selectstart",this.ReturnFalse);this.XBrowserAddHandler(u,"dragstart",this.ReturnFalse);this.XBrowserAddHandler(s,"contextmenu",this.ReturnFalse);this.XBrowserAddHandler(s,"selectstart",this.ReturnFalse);this.XBrowserAddHandler(s,"dragstart",this.ReturnFalse);this.DisableSelection();if(isIE){this.XBrowserAddHandler(s,b,this.MouseDown);this.XBrowserAddHandler(s,mouseUpEvent,this.MouseUp);this.XBrowserAddHandler(s,mouseMoveEvent,this.MouseMove)}else{this.XBrowserAddHandler(u,b,this.MouseDown);this.XBrowserAddHandler(u,mouseUpEvent,this.MouseUp);this.XBrowserAddHandler(u,mouseMoveEvent,this.MouseMove)}ShowDebug("Ready")}else{ShowDebug("Error initializing signature control")}}}};