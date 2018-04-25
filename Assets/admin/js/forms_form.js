!function(t){var e={};function n(o){if(e[o])return e[o].exports;var i=e[o]={i:o,l:!1,exports:{}};return t[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:o})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=2)}([,,function(t,e,n){t.exports=n(3)},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(4),i=n.n(o);new Vue({el:"#formApp",components:{"form-field":n(6),draggable:i.a},data:{languages:JSON.parse($("#languages").val()),formFields:Object.values(JSON.parse($("#currentFields").val())),availableFields:[{name:"Text",type:"text"},{name:"Textarea",type:"textarea"},{name:"Select",type:"select"},{name:"Checkbox",type:"checkbox"},{name:"File",type:"file"},{name:"Email",type:"email"},{name:"Number",type:"number"}]},mounted:function(){this.$on("remove-field",function(t){this.removeField(t)}.bind(this))},methods:{addField:function(t){var e={};$.each(this.languages,function(t,n){e[n.iso_code]={name:"Unnamed field"}}),this.formFields.push({id:this.getNextId(this.formFields,"id"),type:t.type,type_name:t.name,translations:e,order:this.getNextId(this.formFields,"order")})},removeField:function(t){var e=this.findIndex("id",t.id);-1!==e&&this.formFields.splice(e,1)},updateOrder:function(t){this.formFields[t.newIndex].order=t.newIndex+1,this.formFields[t.oldIndex].order=t.oldIndex+1},findIndex:function(t,e){var n=-1;return this.formFields.some(function(o,i){if(o[t]===e)return n=i,!0}),n},getNextId:function(t,e){var n=t.map(function(t){return t[e]}),o=Math.max.apply(null,n);return o!==-1/0?o+1:1}}})},function(t,e,n){"use strict";var o=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(t[o]=n[o])}return t};function i(t){if(Array.isArray(t)){for(var e=0,n=Array(t.length);e<t.length;e++)n[e]=t[e];return n}return Array.from(t)}!function(){function e(t){function e(t){t.parentElement.removeChild(t)}function n(t,e,n){var o=0===n?t.children[0]:t.children[n-1].nextSibling;t.insertBefore(e,o)}function a(t,e){var n=this;this.$nextTick(function(){return n.$emit(t.toLowerCase(),e)})}var r=["Start","Add","Remove","Update","End"],s=["Choose","Sort","Filter","Clone"],l=["Move"].concat(r,s).map(function(t){return"on"+t}),d=null;return{name:"draggable",props:{options:Object,list:{type:Array,required:!1,default:null},value:{type:Array,required:!1,default:null},noTransitionOnDrag:{type:Boolean,default:!1},clone:{type:Function,default:function(t){return t}},element:{type:String,default:"div"},move:{type:Function,default:null},componentData:{type:Object,required:!1,default:null}},data:function(){return{transitionMode:!1,noneFunctionalComponentMode:!1,init:!1}},render:function(t){var e=this.$slots.default;if(e&&1===e.length){var n=e[0];n.componentOptions&&"transition-group"===n.componentOptions.tag&&(this.transitionMode=!0)}var o=e,a=this.$slots.footer;a&&(o=e?[].concat(i(e),i(a)):[].concat(i(a)));var r=null,s=function(t,e){r=function(t,e,n){return void 0==n?t:((t=null==t?{}:t)[e]=n,t)}(r,t,e)};if(s("attrs",this.$attrs),this.componentData){var l=this.componentData,d=l.on,c=l.props;s("on",d),s("props",c)}return t(this.element,r,o)},mounted:function(){var e=this;if(this.noneFunctionalComponentMode=this.element.toLowerCase()!==this.$el.nodeName.toLowerCase(),this.noneFunctionalComponentMode&&this.transitionMode)throw new Error("Transition-group inside component is not supported. Please alter element value or remove transition-group. Current element value: "+this.element);var n={};r.forEach(function(t){n["on"+t]=function(t){var e=this;return function(n){null!==e.realList&&e["onDrag"+t](n),a.call(e,t,n)}}.call(e,t)}),s.forEach(function(t){n["on"+t]=a.bind(e,t)});var i=o({},this.options,n,{onMove:function(t,n){return e.onDragMove(t,n)}});!("draggable"in i)&&(i.draggable=">*"),this._sortable=new t(this.rootContainer,i),this.computeIndexes()},beforeDestroy:function(){this._sortable.destroy()},computed:{rootContainer:function(){return this.transitionMode?this.$el.children[0]:this.$el},isCloning:function(){return!!this.options&&!!this.options.group&&"clone"===this.options.group.pull},realList:function(){return this.list?this.list:this.value}},watch:{options:{handler:function(t){for(var e in t)-1==l.indexOf(e)&&this._sortable.option(e,t[e])},deep:!0},realList:function(){this.computeIndexes()}},methods:{getChildrenNodes:function(){if(this.init||(this.noneFunctionalComponentMode=this.noneFunctionalComponentMode&&1==this.$children.length,this.init=!0),this.noneFunctionalComponentMode)return this.$children[0].$slots.default;var t=this.$slots.default;return this.transitionMode?t[0].child.$slots.default:t},computeIndexes:function(){var t=this;this.$nextTick(function(){t.visibleIndexes=function(t,e,n){if(!t)return[];var o=t.map(function(t){return t.elm}),a=[].concat(i(e)).map(function(t){return o.indexOf(t)});return n?a.filter(function(t){return-1!==t}):a}(t.getChildrenNodes(),t.rootContainer.children,t.transitionMode)})},getUnderlyingVm:function(t){var e=function(t,e){return t.map(function(t){return t.elm}).indexOf(e)}(this.getChildrenNodes()||[],t);return-1===e?null:{index:e,element:this.realList[e]}},getUnderlyingPotencialDraggableComponent:function(t){var e=t.__vue__;return e&&e.$options&&"transition-group"===e.$options._componentTag?e.$parent:e},emitChanges:function(t){var e=this;this.$nextTick(function(){e.$emit("change",t)})},alterList:function(t){if(this.list)t(this.list);else{var e=[].concat(i(this.value));t(e),this.$emit("input",e)}},spliceList:function(){var t=arguments,e=function(e){return e.splice.apply(e,t)};this.alterList(e)},updatePosition:function(t,e){var n=function(n){return n.splice(e,0,n.splice(t,1)[0])};this.alterList(n)},getRelatedContextFromMoveEvent:function(t){var e=t.to,n=t.related,i=this.getUnderlyingPotencialDraggableComponent(e);if(!i)return{component:i};var a=i.realList,r={list:a,component:i};if(e!==n&&a&&i.getUnderlyingVm){var s=i.getUnderlyingVm(n);if(s)return o(s,r)}return r},getVmIndex:function(t){var e=this.visibleIndexes,n=e.length;return t>n-1?n:e[t]},getComponent:function(){return this.$slots.default[0].componentInstance},resetTransitionData:function(t){if(this.noTransitionOnDrag&&this.transitionMode){this.getChildrenNodes()[t].data=null;var e=this.getComponent();e.children=[],e.kept=void 0}},onDragStart:function(t){this.context=this.getUnderlyingVm(t.item),t.item._underlying_vm_=this.clone(this.context.element),d=t.item},onDragAdd:function(t){var n=t.item._underlying_vm_;if(void 0!==n){e(t.item);var o=this.getVmIndex(t.newIndex);this.spliceList(o,0,n),this.computeIndexes();var i={element:n,newIndex:o};this.emitChanges({added:i})}},onDragRemove:function(t){if(n(this.rootContainer,t.item,t.oldIndex),this.isCloning)e(t.clone);else{var o=this.context.index;this.spliceList(o,1);var i={element:this.context.element,oldIndex:o};this.resetTransitionData(o),this.emitChanges({removed:i})}},onDragUpdate:function(t){e(t.item),n(t.from,t.item,t.oldIndex);var o=this.context.index,i=this.getVmIndex(t.newIndex);this.updatePosition(o,i);var a={element:this.context.element,oldIndex:o,newIndex:i};this.emitChanges({moved:a})},computeFutureIndex:function(t,e){if(!t.element)return 0;var n=[].concat(i(e.to.children)).filter(function(t){return"none"!==t.style.display}),o=n.indexOf(e.related),a=t.component.getVmIndex(o);return-1!=n.indexOf(d)||!e.willInsertAfter?a:a+1},onDragMove:function(t,e){var n=this.move;if(!n||!this.realList)return!0;var i=this.getRelatedContextFromMoveEvent(t),a=this.context,r=this.computeFutureIndex(i,t);return o(a,{futureIndex:r}),o(t,{relatedContext:i,draggedContext:a}),n(t,e)},onDragEnd:function(t){this.computeIndexes(),d=null}}}}Array.from||(Array.from=function(t){return[].slice.call(t)});var a=n(5);t.exports=e(a)}()},function(t,e,n){var o,i;!function(a){"use strict";void 0===(i="function"==typeof(o=a)?o.call(e,n,e,t):o)||(t.exports=i)}(function(){"use strict";if("undefined"==typeof window||!window.document)return function(){throw new Error("Sortable.js requires a window with a document")};var t,e,n,o,i,a,r,s,l,d,c,u,p,h,f,m,v,g,_,b,y,x={},C=/\s+/g,D=/left|right|inline/,w="Sortable"+(new Date).getTime(),T=window,S=T.document,E=T.parseInt,O=T.setTimeout,k=T.jQuery||T.Zepto,N=T.Polymer,I=!1,F="draggable"in S.createElement("div"),P=!navigator.userAgent.match(/(?:Trident.*rv[ :]?11\.|msie)/i)&&((y=S.createElement("x")).style.cssText="pointer-events:auto","auto"===y.style.pointerEvents),$=!1,A=Math.abs,M=Math.min,R=[],B=[],L=ot(function(t,e,n){if(n&&e.scroll){var o,i,a,r,c,u,p=n[w],h=e.scrollSensitivity,f=e.scrollSpeed,m=t.clientX,v=t.clientY,g=window.innerWidth,_=window.innerHeight;if(l!==n&&(s=e.scroll,l=n,d=e.scrollFn,!0===s)){s=n;do{if(s.offsetWidth<s.scrollWidth||s.offsetHeight<s.scrollHeight)break}while(s=s.parentNode)}s&&(o=s,i=s.getBoundingClientRect(),a=(A(i.right-m)<=h)-(A(i.left-m)<=h),r=(A(i.bottom-v)<=h)-(A(i.top-v)<=h)),a||r||(r=(_-v<=h)-(v<=h),((a=(g-m<=h)-(m<=h))||r)&&(o=T)),x.vx===a&&x.vy===r&&x.el===o||(x.el=o,x.vx=a,x.vy=r,clearInterval(x.pid),o&&(x.pid=setInterval(function(){if(u=r?r*f:0,c=a?a*f:0,"function"==typeof d)return d.call(p,c,u,t);o===T?T.scrollTo(T.pageXOffset+c,T.pageYOffset+u):(o.scrollTop+=u,o.scrollLeft+=c)},24)))}},30),U=function(t){function e(t,e){return void 0!==t&&!0!==t||(t=n.name),"function"==typeof t?t:function(n,o){var i=o.options.group.name;return e?t:t&&(t.join?t.indexOf(i)>-1:i==t)}}var n={},o=t.group;o&&"object"==typeof o||(o={name:o}),n.name=o.name,n.checkPull=e(o.pull,!0),n.checkPut=e(o.put),n.revertClone=o.revertClone,t.group=n};try{window.addEventListener("test",null,Object.defineProperty({},"passive",{get:function(){I={capture:!1,passive:!1}}}))}catch(t){}function Y(t,e){if(!t||!t.nodeType||1!==t.nodeType)throw"Sortable: `el` must be HTMLElement, and not "+{}.toString.call(t);this.el=t,this.options=e=it({},e),t[w]=this;var n={group:Math.random(),sort:!0,disabled:!1,store:null,handle:null,scroll:!0,scrollSensitivity:30,scrollSpeed:10,draggable:/[uo]l/i.test(t.nodeName)?"li":">*",ghostClass:"sortable-ghost",chosenClass:"sortable-chosen",dragClass:"sortable-drag",ignore:"a, img",filter:null,preventOnFilter:!0,animation:0,setData:function(t,e){t.setData("Text",e.textContent)},dropBubble:!1,dragoverBubble:!1,dataIdAttr:"data-id",delay:0,forceFallback:!1,fallbackClass:"sortable-fallback",fallbackOnBody:!1,fallbackTolerance:0,fallbackOffset:{x:0,y:0},supportPointer:!1!==Y.supportPointer};for(var o in n)!(o in e)&&(e[o]=n[o]);for(var i in U(e),this)"_"===i.charAt(0)&&"function"==typeof this[i]&&(this[i]=this[i].bind(this));this.nativeDraggable=!e.forceFallback&&F,q(t,"mousedown",this._onTapStart),q(t,"touchstart",this._onTapStart),e.supportPointer&&q(t,"pointerdown",this._onTapStart),this.nativeDraggable&&(q(t,"dragover",this),q(t,"dragenter",this)),B.push(this._onDragOver),e.store&&this.sort(e.store.get(this))}function X(e,n){"clone"!==e.lastPullMode&&(n=!0),o&&o.state!==n&&(J(o,"display",n?"none":""),n||o.state&&(e.options.group.revertClone?(i.insertBefore(o,a),e._animate(t,o)):i.insertBefore(o,t)),o.state=n)}function j(t,e,n){if(t){n=n||S;do{if(">*"===e&&t.parentNode===n||nt(t,e))return t}while(t=V(t))}return null}function V(t){var e=t.host;return e&&e.nodeType?e:t.parentNode}function q(t,e,n){t.addEventListener(e,n,I)}function H(t,e,n){t.removeEventListener(e,n,I)}function W(t,e,n){if(t)if(t.classList)t.classList[n?"add":"remove"](e);else{var o=(" "+t.className+" ").replace(C," ").replace(" "+e+" "," ");t.className=(o+(n?" "+e:"")).replace(C," ")}}function J(t,e,n){var o=t&&t.style;if(o){if(void 0===n)return S.defaultView&&S.defaultView.getComputedStyle?n=S.defaultView.getComputedStyle(t,""):t.currentStyle&&(n=t.currentStyle),void 0===e?n:n[e];e in o||(e="-webkit-"+e),o[e]=n+("string"==typeof n?"":"px")}}function z(t,e,n){if(t){var o=t.getElementsByTagName(e),i=0,a=o.length;if(n)for(;i<a;i++)n(o[i],i);return o}return[]}function G(t,e,n,i,a,r,s,l){t=t||e[w];var d=S.createEvent("Event"),c=t.options,u="on"+n.charAt(0).toUpperCase()+n.substr(1);d.initEvent(n,!0,!0),d.to=a||e,d.from=r||e,d.item=i||e,d.clone=o,d.oldIndex=s,d.newIndex=l,e.dispatchEvent(d),c[u]&&c[u].call(t,d)}function K(t,e,n,o,i,a,r,s){var l,d,c=t[w],u=c.options.onMove;return(l=S.createEvent("Event")).initEvent("move",!0,!0),l.to=e,l.from=t,l.dragged=n,l.draggedRect=o,l.related=i||e,l.relatedRect=a||e.getBoundingClientRect(),l.willInsertAfter=s,t.dispatchEvent(l),u&&(d=u.call(c,l,r)),d}function Q(t){t.draggable=!1}function Z(){$=!1}function tt(t){for(var e=t.tagName+t.className+t.src+t.href+t.textContent,n=e.length,o=0;n--;)o+=e.charCodeAt(n);return o.toString(36)}function et(t,e){var n=0;if(!t||!t.parentNode)return-1;for(;t&&(t=t.previousElementSibling);)"TEMPLATE"===t.nodeName.toUpperCase()||">*"!==e&&!nt(t,e)||n++;return n}function nt(t,e){if(t){var n=(e=e.split(".")).shift().toUpperCase(),o=new RegExp("\\s("+e.join("|")+")(?=\\s)","g");return!(""!==n&&t.nodeName.toUpperCase()!=n||e.length&&((" "+t.className+" ").match(o)||[]).length!=e.length)}return!1}function ot(t,e){var n,o;return function(){void 0===n&&(n=arguments,o=this,O(function(){1===n.length?t.call(o,n[0]):t.apply(o,n),n=void 0},e))}}function it(t,e){if(t&&e)for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n]);return t}function at(t){return N&&N.dom?N.dom(t).cloneNode(!0):k?k(t).clone(!0)[0]:t.cloneNode(!0)}function rt(t){return O(t,0)}function st(t){return clearTimeout(t)}return Y.prototype={constructor:Y,_onTapStart:function(e){var n,o=this,i=this.el,a=this.options,s=a.preventOnFilter,l=e.type,d=e.touches&&e.touches[0],c=(d||e).target,u=e.target.shadowRoot&&e.path&&e.path[0]||c,p=a.filter;if(function(t){var e=t.getElementsByTagName("input"),n=e.length;for(;n--;){var o=e[n];o.checked&&R.push(o)}}(i),!t&&!(/mousedown|pointerdown/.test(l)&&0!==e.button||a.disabled)&&!u.isContentEditable&&(c=j(c,a.draggable,i))&&r!==c){if(n=et(c,a.draggable),"function"==typeof p){if(p.call(this,e,c,this))return G(o,u,"filter",c,i,i,n),void(s&&e.preventDefault())}else if(p&&(p=p.split(",").some(function(t){if(t=j(u,t.trim(),i))return G(o,t,"filter",c,i,i,n),!0})))return void(s&&e.preventDefault());a.handle&&!j(u,a.handle,i)||this._prepareDragStart(e,d,c,n)}},_prepareDragStart:function(n,o,s,l){var d,c=this,u=c.el,p=c.options,f=u.ownerDocument;s&&!t&&s.parentNode===u&&(g=n,i=u,e=(t=s).parentNode,a=t.nextSibling,r=s,m=p.group,h=l,this._lastX=(o||n).clientX,this._lastY=(o||n).clientY,t.style["will-change"]="all",d=function(){c._disableDelayedDrag(),t.draggable=c.nativeDraggable,W(t,p.chosenClass,!0),c._triggerDragStart(n,o),G(c,i,"choose",t,i,i,h)},p.ignore.split(",").forEach(function(e){z(t,e.trim(),Q)}),q(f,"mouseup",c._onDrop),q(f,"touchend",c._onDrop),q(f,"touchcancel",c._onDrop),q(f,"selectstart",c),p.supportPointer&&q(f,"pointercancel",c._onDrop),p.delay?(q(f,"mouseup",c._disableDelayedDrag),q(f,"touchend",c._disableDelayedDrag),q(f,"touchcancel",c._disableDelayedDrag),q(f,"mousemove",c._disableDelayedDrag),q(f,"touchmove",c._disableDelayedDrag),p.supportPointer&&q(f,"pointermove",c._disableDelayedDrag),c._dragStartTimer=O(d,p.delay)):d())},_disableDelayedDrag:function(){var t=this.el.ownerDocument;clearTimeout(this._dragStartTimer),H(t,"mouseup",this._disableDelayedDrag),H(t,"touchend",this._disableDelayedDrag),H(t,"touchcancel",this._disableDelayedDrag),H(t,"mousemove",this._disableDelayedDrag),H(t,"touchmove",this._disableDelayedDrag),H(t,"pointermove",this._disableDelayedDrag)},_triggerDragStart:function(e,n){(n=n||("touch"==e.pointerType?e:null))?(g={target:t,clientX:n.clientX,clientY:n.clientY},this._onDragStart(g,"touch")):this.nativeDraggable?(q(t,"dragend",this),q(i,"dragstart",this._onDragStart)):this._onDragStart(g,!0);try{S.selection?rt(function(){S.selection.empty()}):window.getSelection().removeAllRanges()}catch(t){}},_dragStarted:function(){if(i&&t){var e=this.options;W(t,e.ghostClass,!0),W(t,e.dragClass,!1),Y.active=this,G(this,i,"start",t,i,i,h)}else this._nulling()},_emulateDragOver:function(){if(_){if(this._lastX===_.clientX&&this._lastY===_.clientY)return;this._lastX=_.clientX,this._lastY=_.clientY,P||J(n,"display","none");var t=S.elementFromPoint(_.clientX,_.clientY),e=t,o=B.length;if(t&&t.shadowRoot&&(e=t=t.shadowRoot.elementFromPoint(_.clientX,_.clientY)),e)do{if(e[w]){for(;o--;)B[o]({clientX:_.clientX,clientY:_.clientY,target:t,rootEl:e});break}t=e}while(e=e.parentNode);P||J(n,"display","")}},_onTouchMove:function(t){if(g){var e=this.options,o=e.fallbackTolerance,i=e.fallbackOffset,a=t.touches?t.touches[0]:t,r=a.clientX-g.clientX+i.x,s=a.clientY-g.clientY+i.y,l=t.touches?"translate3d("+r+"px,"+s+"px,0)":"translate("+r+"px,"+s+"px)";if(!Y.active){if(o&&M(A(a.clientX-this._lastX),A(a.clientY-this._lastY))<o)return;this._dragStarted()}this._appendGhost(),b=!0,_=a,J(n,"webkitTransform",l),J(n,"mozTransform",l),J(n,"msTransform",l),J(n,"transform",l),t.preventDefault()}},_appendGhost:function(){if(!n){var e,o=t.getBoundingClientRect(),a=J(t),r=this.options;W(n=t.cloneNode(!0),r.ghostClass,!1),W(n,r.fallbackClass,!0),W(n,r.dragClass,!0),J(n,"top",o.top-E(a.marginTop,10)),J(n,"left",o.left-E(a.marginLeft,10)),J(n,"width",o.width),J(n,"height",o.height),J(n,"opacity","0.8"),J(n,"position","fixed"),J(n,"zIndex","100000"),J(n,"pointerEvents","none"),r.fallbackOnBody&&S.body.appendChild(n)||i.appendChild(n),e=n.getBoundingClientRect(),J(n,"width",2*o.width-e.width),J(n,"height",2*o.height-e.height)}},_onDragStart:function(e,n){var a=this,r=e.dataTransfer,s=a.options;a._offUpEvents(),m.checkPull(a,a,t,e)&&((o=at(t)).draggable=!1,o.style["will-change"]="",J(o,"display","none"),W(o,a.options.chosenClass,!1),a._cloneId=rt(function(){i.insertBefore(o,t),G(a,i,"clone",t)})),W(t,s.dragClass,!0),n?("touch"===n?(q(S,"touchmove",a._onTouchMove),q(S,"touchend",a._onDrop),q(S,"touchcancel",a._onDrop),s.supportPointer&&(q(S,"pointermove",a._onTouchMove),q(S,"pointerup",a._onDrop))):(q(S,"mousemove",a._onTouchMove),q(S,"mouseup",a._onDrop)),a._loopId=setInterval(a._emulateDragOver,50)):(r&&(r.effectAllowed="move",s.setData&&s.setData.call(a,r,t)),q(S,"drop",a),a._dragStartId=rt(a._dragStarted))},_onDragOver:function(r){var s,l,d,h,f=this.el,g=this.options,_=g.group,y=Y.active,x=m===_,C=!1,T=g.sort;if(void 0!==r.preventDefault&&(r.preventDefault(),!g.dragoverBubble&&r.stopPropagation()),!t.animated&&(b=!0,y&&!g.disabled&&(x?T||(h=!i.contains(t)):v===this||(y.lastPullMode=m.checkPull(this,y,t,r))&&_.checkPut(this,y,t,r))&&(void 0===r.rootEl||r.rootEl===this.el))){if(L(r,g,this.el),$)return;if(s=j(r.target,g.draggable,f),l=t.getBoundingClientRect(),v!==this&&(v=this,C=!0),h)return X(y,!0),e=i,void(o||a?i.insertBefore(t,o||a):T||i.appendChild(t));if(0===f.children.length||f.children[0]===n||f===r.target&&function(t,e){var n=t.lastElementChild.getBoundingClientRect();return e.clientY-(n.top+n.height)>5||e.clientX-(n.left+n.width)>5}(f,r)){if(0!==f.children.length&&f.children[0]!==n&&f===r.target&&(s=f.lastElementChild),s){if(s.animated)return;d=s.getBoundingClientRect()}X(y,x),!1!==K(i,f,t,l,s,d,r)&&(t.contains(f)||(f.appendChild(t),e=f),this._animate(l,t),s&&this._animate(d,s))}else if(s&&!s.animated&&s!==t&&void 0!==s.parentNode[w]){c!==s&&(c=s,u=J(s),p=J(s.parentNode));var S=(d=s.getBoundingClientRect()).right-d.left,E=d.bottom-d.top,k=D.test(u.cssFloat+u.display)||"flex"==p.display&&0===p["flex-direction"].indexOf("row"),N=s.offsetWidth>t.offsetWidth,I=s.offsetHeight>t.offsetHeight,F=(k?(r.clientX-d.left)/S:(r.clientY-d.top)/E)>.5,P=s.nextElementSibling,A=!1;if(k){var M=t.offsetTop,R=s.offsetTop;A=M===R?s.previousElementSibling===t&&!N||F&&N:s.previousElementSibling===t||t.previousElementSibling===s?(r.clientY-d.top)/E>.5:R>M}else C||(A=P!==t&&!I||F&&I);var B=K(i,f,t,l,s,d,r,A);!1!==B&&(1!==B&&-1!==B||(A=1===B),$=!0,O(Z,30),X(y,x),t.contains(f)||(A&&!P?f.appendChild(t):s.parentNode.insertBefore(t,A?P:s)),e=t.parentNode,this._animate(l,t),this._animate(d,s))}}},_animate:function(t,e){var n=this.options.animation;if(n){var o=e.getBoundingClientRect();1===t.nodeType&&(t=t.getBoundingClientRect()),J(e,"transition","none"),J(e,"transform","translate3d("+(t.left-o.left)+"px,"+(t.top-o.top)+"px,0)"),e.offsetWidth,J(e,"transition","all "+n+"ms"),J(e,"transform","translate3d(0,0,0)"),clearTimeout(e.animated),e.animated=O(function(){J(e,"transition",""),J(e,"transform",""),e.animated=!1},n)}},_offUpEvents:function(){var t=this.el.ownerDocument;H(S,"touchmove",this._onTouchMove),H(S,"pointermove",this._onTouchMove),H(t,"mouseup",this._onDrop),H(t,"touchend",this._onDrop),H(t,"pointerup",this._onDrop),H(t,"touchcancel",this._onDrop),H(t,"pointercancel",this._onDrop),H(t,"selectstart",this)},_onDrop:function(r){var s=this.el,l=this.options;clearInterval(this._loopId),clearInterval(x.pid),clearTimeout(this._dragStartTimer),st(this._cloneId),st(this._dragStartId),H(S,"mouseover",this),H(S,"mousemove",this._onTouchMove),this.nativeDraggable&&(H(S,"drop",this),H(s,"dragstart",this._onDragStart)),this._offUpEvents(),r&&(b&&(r.preventDefault(),!l.dropBubble&&r.stopPropagation()),n&&n.parentNode&&n.parentNode.removeChild(n),i!==e&&"clone"===Y.active.lastPullMode||o&&o.parentNode&&o.parentNode.removeChild(o),t&&(this.nativeDraggable&&H(t,"dragend",this),Q(t),t.style["will-change"]="",W(t,this.options.ghostClass,!1),W(t,this.options.chosenClass,!1),G(this,i,"unchoose",t,e,i,h),i!==e?(f=et(t,l.draggable))>=0&&(G(null,e,"add",t,e,i,h,f),G(this,i,"remove",t,e,i,h,f),G(null,e,"sort",t,e,i,h,f),G(this,i,"sort",t,e,i,h,f)):t.nextSibling!==a&&(f=et(t,l.draggable))>=0&&(G(this,i,"update",t,e,i,h,f),G(this,i,"sort",t,e,i,h,f)),Y.active&&(null!=f&&-1!==f||(f=h),G(this,i,"end",t,e,i,h,f),this.save()))),this._nulling()},_nulling:function(){i=t=e=n=a=o=r=s=l=g=_=b=f=c=u=v=m=Y.active=null,R.forEach(function(t){t.checked=!0}),R.length=0},handleEvent:function(e){switch(e.type){case"drop":case"dragend":this._onDrop(e);break;case"dragover":case"dragenter":t&&(this._onDragOver(e),function(t){t.dataTransfer&&(t.dataTransfer.dropEffect="move");t.preventDefault()}(e));break;case"mouseover":this._onDrop(e);break;case"selectstart":e.preventDefault()}},toArray:function(){for(var t,e=[],n=this.el.children,o=0,i=n.length,a=this.options;o<i;o++)j(t=n[o],a.draggable,this.el)&&e.push(t.getAttribute(a.dataIdAttr)||tt(t));return e},sort:function(t){var e={},n=this.el;this.toArray().forEach(function(t,o){var i=n.children[o];j(i,this.options.draggable,n)&&(e[t]=i)},this),t.forEach(function(t){e[t]&&(n.removeChild(e[t]),n.appendChild(e[t]))})},save:function(){var t=this.options.store;t&&t.set(this)},closest:function(t,e){return j(t,e||this.options.draggable,this.el)},option:function(t,e){var n=this.options;if(void 0===e)return n[t];n[t]=e,"group"===t&&U(n)},destroy:function(){var t=this.el;t[w]=null,H(t,"mousedown",this._onTapStart),H(t,"touchstart",this._onTapStart),H(t,"pointerdown",this._onTapStart),this.nativeDraggable&&(H(t,"dragover",this),H(t,"dragenter",this)),Array.prototype.forEach.call(t.querySelectorAll("[draggable]"),function(t){t.removeAttribute("draggable")}),B.splice(B.indexOf(this._onDragOver),1),this._onDrop(),this.el=t=null}},q(S,"touchmove",function(t){Y.active&&t.preventDefault()}),Y.utils={on:q,off:H,css:J,find:z,is:function(t,e){return!!j(t,e,t)},extend:it,throttle:ot,closest:j,toggleClass:W,clone:at,index:et,nextTick:rt,cancelNextTick:st},Y.create=function(t,e){return new Y(t,e)},Y.version="1.7.0",Y})},function(t,e,n){var o=n(7)(n(8),n(9),!1,null,null,null);t.exports=o.exports},function(t,e){t.exports=function(t,e,n,o,i,a){var r,s=t=t||{},l=typeof t.default;"object"!==l&&"function"!==l||(r=t,s=t.default);var d,c="function"==typeof s?s.options:s;if(e&&(c.render=e.render,c.staticRenderFns=e.staticRenderFns,c._compiled=!0),n&&(c.functional=!0),i&&(c._scopeId=i),a?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},c._ssrRegister=d):o&&(d=o),d){var u=c.functional,p=u?c.render:c.beforeCreate;u?(c._injectStyles=d,c.render=function(t,e){return d.call(e),p(t,e)}):c.beforeCreate=p?[].concat(p,d):[d]}return{esModule:r,exports:s,options:c}}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};e.default={components:{select2:window.Select2},props:{data:Object,languages:Array},data:function(){return{htmlAttributes:[{id:"required",text:"Required"},{id:"disabled",text:"Disabled"}],validationRules:[{id:"accepted",text:"Accepted"},{id:"email",text:"Email"},{id:"file",text:"File"},{id:"image",text:"Image"},{id:"required",text:"Required"},{id:"unique",text:"Unique"},{id:"numeric",text:"Numeric"},{id:"url",text:"URL"}],optionTypes:[{id:"function",text:"Function"},{id:"data",text:"Data"}],optionsData:[],optionsFunction:"",optionsType:this.data.meta&&void 0!==this.data.meta.options_type?this.data.meta.options_type:"data",attributes:this.data.meta?this.data.meta.attributes:[],validation:this.data.meta?this.data.meta.validation:[]}},mounted:function(){this.setOptions()},computed:{model:function(){var t=JSON.parse(JSON.stringify(this.data));return $.each(this.languages,function(e,n){var o=n.iso_code,i=t.translations[o],a=i?i.label:"",r=i?i.name:"",s=i?i.placeholder:"";t.translations[o]={label:a,name:r,placeholder:s}}),t}},methods:{addOption:function(){this.optionsData.push({key:"",value:""})},removeOption:function(t){var e=this;swal({title:"Are you sure?",text:"You will not be able to restore this data!",type:"warning",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:"Delete!"}).then(function(){Vue.delete(e.options,t),toastr.success("Option successfully removed!")}).catch(swal.noop)},remove:function(t){var e=this;swal({title:"Are you sure?",text:"You will not be able to restore this data!",type:"warning",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:"Delete!"}).then(function(){e.$emit("remove-field",t),toastr.success("Field successfully removed!")}).catch(swal.noop)},setOptions:function(){var t=this.data.meta?this.data.meta.options:[];if("object"===(void 0===t?"undefined":o(t))){var e=[];for(var n in t)t.hasOwnProperty(n)&&e.push({key:n,value:t[n]});this.optionsType="data",this.optionsData=e}else this.optionsType="function",this.optionsFunction=t}}}},function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"panel panel-default"},[n("div",{staticClass:"panel-heading",attrs:{role:"tab",id:"heading-"+t.model.id}},[n("h4",{staticClass:"panel-title"},[t._m(0),t._v(" "),n("a",{attrs:{role:"button","data-toggle":"collapse","data-parent":"#accordion",href:"#collapse-"+t.model.id,"aria-expanded":"true","aria-controls":"collapse-"+t.model.id}},t._l(t.model.translations,function(e,o){return n("div",{staticClass:"pull-left m-l-5"},[t._v("\n                        "+t._s(o.toUpperCase())+": "+t._s(e.name)+"\n                    ")])})),t._v(" "),n("div",{staticClass:"pull-right"},[t._v("\n                    "+t._s(t.model.type_name)+"\n                ")])])]),t._v(" "),n("div",{staticClass:"panel-collapse collapse",attrs:{id:"collapse-"+t.model.id,role:"tabpanel","aria-labelledby":"heading-"+t.model.id}},[n("div",{staticClass:"panel-body"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.model.id,expression:"model.id"}],attrs:{type:"hidden",name:"fields["+t.model.id+"][id]"},domProps:{value:t.model.id,value:t.model.id},on:{input:function(e){e.target.composing||t.$set(t.model,"id",e.target.value)}}}),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.model.order,expression:"model.order"}],attrs:{type:"hidden",name:"fields["+t.model.id+"][order]"},domProps:{value:t.model.order,value:t.model.order},on:{input:function(e){e.target.composing||t.$set(t.model,"order",e.target.value)}}}),t._v(" "),n("input",{attrs:{type:"hidden",name:"fields["+t.model.id+"][type]"},domProps:{value:t.model.type}}),t._v(" "),n("input",{attrs:{type:"hidden",name:"fields["+t.model.id+"][type_name]"},domProps:{value:t.model.type_name}}),t._v(" "),n("div",{staticClass:"form-group"},[n("label",[t._v("Key")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.data.key,expression:"data.key"}],staticClass:"form-control",attrs:{name:"fields["+t.model.id+"][key]",placeholder:"For example, phone_number"},domProps:{value:t.data.key},on:{input:function(e){e.target.composing||t.$set(t.data,"key",e.target.value)}}})]),t._v(" "),n("div",{staticClass:"form-group"},[n("label",[t._v("Show label?")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.data.show_label,expression:"data.show_label"}],attrs:{type:"checkbox",name:"fields["+t.model.id+"][show_label]"},domProps:{checked:Array.isArray(t.data.show_label)?t._i(t.data.show_label,null)>-1:t.data.show_label},on:{change:function(e){var n=t.data.show_label,o=e.target,i=!!o.checked;if(Array.isArray(n)){var a=t._i(n,null);o.checked?a<0&&(t.data.show_label=n.concat([null])):a>-1&&(t.data.show_label=n.slice(0,a).concat(n.slice(a+1)))}else t.$set(t.data,"show_label",i)}}})]),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-12 col-md-12 col-sm-12 col-xs-6"},[n("ul",{staticClass:"nav nav-tabs",attrs:{role:"tablist"}},t._l(t.languages,function(e,o){return n("li",{class:{active:0===o},attrs:{role:"presentation"}},[n("a",{attrs:{href:"#fields-"+t.model.id+"-"+e.iso_code,"aria-controls":e.iso_code,role:"tab","data-toggle":"tab"}},[t._v("\n                                    "+t._s(e.title_localized)+"\n                                ")])])})),t._v(" "),n("div",{staticClass:"tab-content"},t._l(t.languages,function(e,o){return n("div",{staticClass:"tab-pane",class:{active:0===o},attrs:{role:"tabpanel",id:"fields-"+t.model.id+"-"+e.iso_code}},[n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-6"},[n("div",{staticClass:"form-group"},[n("label",{domProps:{textContent:t._s("Label "+e.iso_code.toUpperCase())}}),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.model.translations[String(e.iso_code)].label,expression:"model.translations[String(language.iso_code)].label"}],staticClass:"form-control",attrs:{name:"fields["+t.model.id+"][translations]["+e.iso_code+"][label]",placeholder:"For example, Phone number"},domProps:{value:t.model.translations[String(e.iso_code)].label},on:{input:function(n){n.target.composing||t.$set(t.model.translations[String(e.iso_code)],"label",n.target.value)}}})])]),t._v(" "),n("div",{staticClass:"col-lg-6"},[n("div",{staticClass:"form-group"},[n("label",{domProps:{textContent:t._s("Placeholder "+e.iso_code.toUpperCase())}}),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.model.translations[String(e.iso_code)].placeholder,expression:"model.translations[String(language.iso_code)].placeholder"}],staticClass:"form-control",attrs:{name:"fields["+t.model.id+"][translations]["+e.iso_code+"][placeholder]",placeholder:"For example, Enter phone number"},domProps:{value:t.model.translations[String(e.iso_code)].placeholder},on:{input:function(n){n.target.composing||t.$set(t.model.translations[String(e.iso_code)],"placeholder",n.target.value)}}})])])])])}))])]),t._v(" "),"select"===t.model.type?n("div",[n("div",{staticClass:"form-group"},[n("label",[t._v("Options")]),t._v(" "),n("br"),t._v(" "),n("select2",{attrs:{data:t.optionTypes,name:"fields["+t.model.id+"][options_type]",placeholder:"Please select"},model:{value:t.optionsType,callback:function(e){t.optionsType=e},expression:"optionsType"}})],1),t._v(" "),"data"===t.optionsType?n("div",{staticClass:"form-group"},[n("button",{staticClass:"btn btn-success btn-xs",attrs:{type:"button"},on:{click:function(e){t.addOption()}}},[n("i",{staticClass:"fa fa-plus"}),t._v(" Add Option\n                        ")]),t._v(" "),n("br"),t._v(" "),t._l(t.optionsData,function(e,o){return n("div",{staticClass:"input-group"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.key,expression:"option.key"}],staticClass:"form-control",attrs:{type:"text",name:"fields["+t.model.id+"][options][key][]"},domProps:{value:e.key},on:{input:function(n){n.target.composing||t.$set(e,"key",n.target.value)}}}),t._v(" "),n("span",{staticClass:"input-group-addon"},[t._v("-")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"option.value"}],staticClass:"form-control",attrs:{type:"text",name:"fields["+t.model.id+"][options][value][]"},domProps:{value:e.value},on:{input:function(n){n.target.composing||t.$set(e,"value",n.target.value)}}}),t._v(" "),n("span",{staticClass:"input-group-addon"},[n("button",{staticClass:"btn btn-danger btn-xs",attrs:{type:"button"},on:{click:function(e){t.removeOption(o)}}},[n("i",{staticClass:"fa fa-trash"}),t._v(" Remove\n                                ")])])])})],2):t._e(),t._v(" "),"function"===t.optionsType?n("div",[n("div",{staticClass:"form-group"},[n("label",[t._v("Function name")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.optionsFunction,expression:"optionsFunction"}],staticClass:"form-control",attrs:{name:"fields["+t.model.id+"][options]",placeholder:"For example, getCountriesList"},domProps:{value:t.optionsFunction},on:{input:function(e){e.target.composing||(t.optionsFunction=e.target.value)}}})])]):t._e()]):t._e(),t._v(" "),n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-6"},[n("div",{staticClass:"form-group"},[n("label",[t._v("Attributes")]),t._v(" "),n("select2",{attrs:{data:t.htmlAttributes,name:"fields["+t.model.id+"][attributes][]",placeholder:"Please select",multiple:!0},model:{value:t.attributes,callback:function(e){t.attributes=e},expression:"attributes"}})],1)]),t._v(" "),n("div",{staticClass:"col-lg-6"},[n("div",{staticClass:"form-group"},[n("label",[t._v("Validation rules")]),t._v(" "),n("select2",{attrs:{data:t.validationRules,name:"fields["+t.model.id+"][validation][]",placeholder:"Please select",multiple:!0},model:{value:t.validation,callback:function(e){t.validation=e},expression:"validation"}})],1)])]),t._v(" "),n("a",{staticClass:"btn btn-xs btn-danger pull-right",attrs:{href:"javascript:;"},on:{click:function(e){t.remove(t.model)}}},[n("i",{staticClass:"fa fa-trash"}),t._v(" Remove\n                ")])])])])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pull-left handle"},[e("i",{staticClass:"fa fa-arrows"})])}]}}]);