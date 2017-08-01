(function (global) {
	var Tao = function (json) {
		json = json ? json : {};
		this.setobj = {
			extend: json.extend ? json.extend : true,
			orient: json.orient ? json.orient : "portrait",
			load: json.load ? json.load : false,
			decode: json.decode ? json.decode : true,
			storage: json.storage ? json.storage : false,
			console: json.console ? json.console : false
		}
		this.extend = Extend;
		this.init();
	}
	var Extend = {
		load: function (e) {
			var TaoLoad = function (objname) {
				this.objname = objname;
				this.load();
				this.result;
			}
			TaoLoad.prototype.load = function () {
				var getstr = new XMLHttpRequest();
				getstr.onprogress = function (e) {
					this.onprogress(e);
				}.bind(this);
				getstr.onloadend = function () {
					this.onloaded(getstr.response);
				}.bind(this)
				getstr.open("GET", this.objname + "", true);
				getstr.send();
				getstr.responseType = "arraybuffer";
			}
			TaoLoad.prototype.onprogress = function () {}
			TaoLoad.prototype.onloaded = function () {}
			return new TaoLoad(e)
		},
		decode: function (e) {
			var TaoDecode = function (y) {
				this.ab = y;
				if (!localStorage.getItem("TZ")) {
					localStorage.setItem("TZ", '{"size":718,"arr":[118,97,114,32,84,97,111,90,61,102,117,110,99,116,105,111,110,40,116,44,101,41,123,116,104,105,115,46,97,98,117,102,102,101,114,61,116,44,116,104,105,115,46,99,97,108,108,98,97,99,107,61,101,44,116,104,105,115,46,85,110,90,40,41,125,59,84,97,111,90,46,112,114,111,116,111,116,121,112,101,46,85,110,90,61,102,117,110,99,116,105,111,110,40,41,123,118,97,114,32,116,61,110,101,119,32,68,97,116,97,86,105,101,119,40,116,104,105,115,46,97,98,117,102,102,101,114,41,46,103,101,116,73,110,116,51,50,40,49,41,44,101,61,110,101,119,32,68,97,116,97,86,105,101,119,40,116,104,105,115,46,97,98,117,102,102,101,114,44,51,50,48,44,116,41,44,105,61,110,101,119,32,66,108,111,98,40,91,101,93,44,123,116,121,112,101,58,34,102,105,108,101,47,116,97,111,34,125,41,44,111,61,110,101,119,32,70,105,108,101,82,101,97,100,101,114,59,116,104,105,115,46,112,105,97,110,121,105,61,51,50,48,43,116,44,111,46,111,110,108,111,97,100,61,102,117,110,99,116,105,111,110,40,41,123,116,104,105,115,46,106,115,111,110,61,74,83,79,78,46,112,97,114,115,101,40,111,46,114,101,115,117,108,116,41,44,116,104,105,115,46,100,101,99,111,100,101,40,41,125,46,98,105,110,100,40,116,104,105,115,41,44,111,46,114,101,97,100,65,115,84,101,120,116,40,105,41,125,44,84,97,111,90,46,112,114,111,116,111,116,121,112,101,46,100,101,99,111,100,101,61,102,117,110,99,116,105,111,110,40,41,123,118,97,114,32,116,61,116,104,105,115,46,112,105,97,110,121,105,59,116,104,105,115,46,114,101,115,117,108,116,61,110,101,119,32,77,97,112,44,116,104,105,115,46,114,101,115,117,108,116,50,61,110,101,119,32,77,97,112,59,102,111,114,40,118,97,114,32,101,61,48,59,101,60,116,104,105,115,46,106,115,111,110,46,110,97,109,101,46,108,101,110,103,116,104,59,101,43,43,41,123,118,97,114,32,105,61,112,97,114,115,101,73,110,116,40,116,104,105,115,46,106,115,111,110,46,115,105,122,101,91,101,93,41,44,111,61,110,101,119,32,68,97,116,97,86,105,101,119,40,116,104,105,115,46,97,98,117,102,102,101,114,44,116,44,105,41,44,115,61,110,101,119,32,66,108,111,98,40,91,111,93,44,123,116,121,112,101,58,116,104,105,115,46,106,115,111,110,46,116,121,112,101,91,101,93,125,41,59,116,43,61,105,59,118,97,114,32,110,61,85,82,76,46,99,114,101,97,116,101,79,98,106,101,99,116,85,82,76,40,115,41,59,116,104,105,115,46,114,101,115,117,108,116,46,115,101,116,40,116,104,105,115,46,106,115,111,110,46,110,97,109,101,91,101,93,44,110,41,44,116,104,105,115,46,114,101,115,117,108,116,50,46,115,101,116,40,116,104,105,115,46,106,115,111,110,46,110,97,109,101,91,101,93,44,115,41,125,116,104,105,115,46,99,97,108,108,98,97,99,107,40,116,104,105,115,46,114,101,115,117,108,116,50,41,125,59]}');
				}
				var bat_json = JSON.parse(localStorage.getItem("TZ"));
				var size = bat_json.size;
				this.xxx = binayUtf8ToString(bat_json.arr, 0);
				this.oScript = document.createElement("script");
				this.oScript.type = "text/javascript";
				this.oScript.innerHTML = this.xxx;
				document.body.appendChild(this.oScript);
				this.decode();
			}
			TaoDecode.prototype.decode = function () {
				new TaoZ(this.ab, function (b) {
					this.onloaded(b);
					document.body.removeChild(this.oScript);
				}.bind(this));
			}
			TaoDecode.prototype.onloaded = function () {}
			return new TaoDecode(e);
		},
		orient: function (e) {
			function Orient(y, callback) {
				this.y = y;
				this.name = this.y == "portrait" ? "锁定" : "开启";
				this.callback = callback || "";
				this.obj = document.createElement('div');
				document.body.appendChild(this.obj);
				this.obj.className = "mod-orient-layer none";
				this.obj.id = "orientLayer";
				this.obj.innerHTML = '<div class="mod-orient-layer__content"><i class="icon mod-orient-layer__icon-orient"></i><div class="mod-orient-layer__desc">为了更好的体验，请' + this.name + '屏幕旋转后浏览</div></div>';
				this.styles = document.createElement('style');
				document.body.appendChild(this.styles);
				this.styles.innerHTML = '@-webkit-keyframes rotation{10%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}50%{transform:rotate(0);-webkit-transform:rotate(0)}60%{transform:rotate(0);-webkit-transform:rotate(0)}90%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}100%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}}.mod-orient-layer{display:none;position:fixed;height:100%;width:100%;left:0;top:0;background:#000;z-index:9997}.mod-orient-layer__content{position:absolute;width:100%;top:45%;margin-top:-75px;text-align:center}.mod-orient-layer__icon-orient{display:inline-block;width:67px;height:109px;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIYAAADaCAMAAABU68ovAAAAXVBMVEUAAAD29vb////x8fH////////x8fH5+fn29vby8vL////5+fn39/f6+vr////x8fH////////+/v7////09PT////x8fH39/f////////////////////x8fH///+WLTLGAAAAHXRSTlMAIpML+gb4ZhHWn1c2gvHBvq1uKJcC6k8b187lQ9yhhboAAAQYSURBVHja7d3blpowFIDhTUIAOchZDkre/zE7ycySrbUUpsRN2/1fzO18KzEqxEVgTiZNfgmmtxRc8iaR8HNe8x4BtjQePKayYCIoyBSgvNNE1AkNSHqZyLqk97EgUCCHBzZ5mkg7ScvIJuIyOyXBRFxgpqWZyGsAZLB1KjsJi8nutHU4JCRbFRH8tmirI9k8Jx2sqNs8K/m0LQkrktO2crgcgXGB4AiTEsB0hJfo9MGgX7CGcYiYwQxmMOOvZwRhBG8tCoMXjBDeXvWCEcHbi14wgCBmMIMZzGAGM5jxETNwzMAxA8cMHDNwzMAxA8cMHDNwzMAxA8cMHDNwzMAxY6E2rUQxnH2tz9cirlJFwFBJedaPnUv0M7++egPDE8iAJcIDmxwH5wwv9vUviw2kLbVO3TJU5uul/EyB0FoLp4x60PdGUd3qPurrWyjGGTc05u+1dcgI7/+tCCPARWGhH7o5Y7RCf+bH9ctXLp6v2BVDxfqz0oPXeSVaNtINo/1SXDv4dck8IIkbhtC2ol+iouEonTBCbYvVMnXOjxww6s/RFrBUpXHh/gw1rHj5d/qhYn9Gpk2FWh6xRBRX5Oj3Znh2Sq49/L6+y8pB26q9GbE2dbA2mVbx6I+7MfBglLCttm73ZQi7AD3iL4HqjFYJHSPRppqaUaJ3ATpGa+ckpGak2hRRMyqjGMkvl+xyFeSMwjAqcsZgGDdyhl0oNTnDN4yenJGZFGxNChP5/Y3efh6SM2rDOJMzboYxkDMqwyjIGcIw6F+io2FU1IxIm1JqRmgXSkvNKNCXeTpGrU0JNSO2c6LIGPgCS8AuDHz9ta0SXWDtxoDRH+MqlbC2Dt2G2JFRadtQZt2qq/orGowdGb2euxYiqWEpVWhTBnszoNAPdStuQwxqf0aocdWKW4Z+DfszIh8pxJqbuCE4YAC+4bm0evtipjpgJHeFnyyt1Ku2xa0bhjxr27p75rECNwyI9ZwvXkHq+7aTaMEV44YYy/spfgjgjNHaWW+GeUhGEX7tLlVinIFDDSgnOwhi1V6bU0b6tVS9eAERe863g4dRrtiHdc6o+nn5vtyVVgR79Cqt4uL6gfHPQyGqtP2vf7HADGbcYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JjhtOM+J/AgT008yDMkN/dPP9hzS8zAMQN3OEYeekp5YU7KOKXwVXqiY+QS7smcinGKABWdiBgpPJTSMHJ4KidhhPBUSMLw4CmPhKHgKUXCkHsygum71ftNSgCX6bsl8FQyfbcL5EdYsDk0R3j7aiA5wpt5AjKg/2gLJEBD/0Hf2OOf/vRrj6z/7GtP4B3nMKyjHA12kIPSjnJs3FEO0TvKkYJHOWCR+rjJH0Vn6fI5PjNbAAAAAElFTkSuQmCC);transform:rotate(90deg);-webkit-transform:rotate(90deg);-webkit-animation:rotation infinite 1.5s ease-in-out;animation:rotation infinite 1.5s ease-in-out;-webkit-background-size:67px;background-size:67px}.mod-orient-layer__desc{margin-top:20px;font-size:15px;color:#fff}.mod-orient-layer__desc{margin-top:20px;font-size:15px;color:#fff}';
				var ori = "onorientationchange" in window ? "orientationchange" : "resize";
				this.orientNotice();
				window.addEventListener(ori, function () {
					setTimeout(function () {
						this.orientNotice();
					}.bind(this), 200);
				}.bind(this));
			}
			Orient.prototype.orientNotice = function () {
				var orient = this.checkDirect();
				if (orient == this.y) {
					this.obj.style.display = "none";
				} else {
					this.obj.style.display = "block";
				}
			}
			Orient.prototype.checkDirect = function () {
				if (document.documentElement.clientHeight >= document.documentElement.clientWidth) {
					return "portrait";
				} else {
					return "landscape";
				}
			}
			return new Orient(e);
		},
		count: function (e) {
			function Count(num) {
				this.gogogo = true;
				this.num = num;
				this.thedate = new Date();
				this.action = this.thedate.getTime();
				this.go();
			}
			Count.prototype.go = function () {
				this.thedate = new Date();
				this.now = this.thedate.getTime();
				this.cha = (this.now - this.action) / 1000;
				this.onplay(this.cha);
				if (this.cha > this.num || !this.gogogo) {
					return;
				}
				window.requestAnimationFrame(function () {
					this.go()
				}.bind(this));
			}
			Count.prototype.pause = function () {
				this.gogogo = false;
			}
			Count.prototype.play = function () {
				this.gogogo = true;
			}
			Count.prototype.onplay = function (e) {}
			return new Count(e);
		},
		touch: function (e) {
			function Touch(obj) {
				var ele = obj ? obj : document;
				var eventstart = "ontouchstart" in window ? "touchstart" : "mousedown";
				var eventend = "ontouchstart" in window ? "touchend" : "mouseup";
				var eventmove = "ontouchstart" in window ? "touchmove" : "mousemove";
				this.beforex = 0, this.afterx = 0;
				this.beforey = 0, this.aftery = 0;
				this.chax = 0, this.chay = 0;
				ele.addEventListener(eventstart, function (e) {
					this.tstart(e);
				}.bind(this));
				ele.addEventListener("touchmove", function (e) {
					this.tmove(e);
				}.bind(this));
				ele.addEventListener(eventend, function (e) {
					this.tend(e);
				}.bind(this));
			}
			Touch.prototype.tmove = function (e) {
				this.afterx = "ontouchstart" in window ? e.targetTouches[0].clientX : e.x;
				this.aftery = "ontouchstart" in window ? e.targetTouches[0].clientY : e.y;
				this.chax = this.afterx - this.beforex;
				this.chay = this.aftery - this.beforey;
				this.beforex = this.afterx;
				this.beforey = this.aftery;
				var cha = {
					x: this.chax,
					y: this.chay,
					clientX: this.afterx,
					clientY: this.aftery,
					moveX: this.chax,
					moveY: this.chay
				}
				this.move(cha);
			}
			Touch.prototype.tstart = function (e) {
				this.beforex = 0, this.afterx = 0;
				this.beforey = 0, this.aftery = 0;
				this.checkx = 0, this.checky = 0;
				this.beforex = this.afterx = "ontouchstart" in window ? e.targetTouches[0].clientX : e.x;
				this.beforey = this.aftery = "ontouchstart" in window ? e.targetTouches[0].clientY : e.y;
				this.checkx = this.beforex;
				this.checky = this.beforey;
				var cha = {
					clientX: this.beforex,
					clientY: this.beforey
				}
				this.start(cha);
			}
			Touch.prototype.tend = function (e) {
				this.afterx = "ontouchstart" in window ? this.afterx : e.x;
				this.aftery = "ontouchstart" in window ? this.aftery : e.y;
				this.chax = this.afterx - this.checkx;
				this.chay = this.aftery - this.checky;
				var cha = {
					x: this.chax,
					y: this.chay,
					clientX: this.afterx,
					clientY: this.aftery,
					moveX: this.chax,
					moveY: this.chay
				}
				this.end(cha);
				this.beforex = this.afterx = 0;
				this.beforey = this.aftery = 0;
				this.checkx = this.checky = 0;
			}
			Touch.prototype.move = function (e) {}
			Touch.prototype.end = function (e) {}
			Touch.prototype.start = function (e) {}
			return new Touch(e);
		},
		shake: function () {
			function Shake() {
				this.last_update = 0;
				this.range = 3000;
				this.x = this.y = this.z = this.last_x = this.last_y = this.last_z = 0;
				window.addEventListener("devicemotion", function (e) {
					this.dm(e);
				}.bind(this));
			}
			Shake.prototype.dm = function (e) {
				var acceleration = e.accelerationIncludingGravity;
				var curTime = new Date().getTime();
				if ((curTime - this.last_update) > 100) {
					var diffTime = curTime - this.last_update;
					this.last_update = curTime;
					this.x = acceleration.x;
					this.y = acceleration.y;
					this.z = acceleration.z;
					var speed = Math.abs(this.x + this.y + this.z - this.last_x - this.last_y - this.last_z) / diffTime * 10000;
					this.end(speed);
					this.last_x = this.x;
					this.last_y = this.y;
					this.last_z = this.z;
				}
			}
			Shake.prototype.end = function () {}
			return new Shake();
		},
		taovideo: function (a, b, c) {
			function TaoVideo(arr, num, arr2) {
				this.arr2 = arr2 ? arr2.slice(0) : [];
				this.arr3 = arr2 ? arr2.slice(0) : [];
				this.frame = num ? num : 16;
				this.switch = false;
				this.duration = arr.length;
				this.currentTime = 0;
				this.arr = arr;
				this.imgarr = [];
				this.movie();
			}
			TaoVideo.prototype.movie = function () {
				if (this.switch) {
					if (this.arr2.indexOf(this.currentTime) > -1) {
						this.switch = false;
						this.arr2.remove(this.currentTime);
						this.stop(this.currentTime);
					} else {
						if(this.imgarr.length == this.duration){
							this.result(this.imgarr[this.currentTime]);
						}else{
							var render = new Image();
							render.onload = function () {
								this.imgarr.push(render);
								this.result(render);
							}.bind(this)
							render.src = this.arr[this.currentTime];
						}
						this.currentTime++;
						if (this.currentTime >= this.duration) {
							this.currentTime = 0;
							this.switch = false;
							this.arr2 = this.arr3.slice(0);
							this.stop(this.currentTime);
						}
					}
				}
				if (this.frame <= 16) {
					window.requestAnimationFrame(function () {
						this.movie();
					}.bind(this));
				} else {
					setTimeout(function () {
						this.movie();
					}.bind(this), this.frame);
				}
			}
			TaoVideo.prototype.result = function () {};
			TaoVideo.prototype.play = function () {
				this.switch = true;
			}
			TaoVideo.prototype.pause = function () {
				this.switch = false;
			}
			TaoVideo.prototype.stop = function () {}
			return new TaoVideo(a, b, c);
		},
		taoaudio: function (a) {
			function TaoAudio(ab) {
				this.ab = ab;
				this.loop = false;
				this.audioCtx = new(window.AudioContext || window.webkitAudioContext);
				this.audioCtx.suspend();
				this.source = this.audioCtx.createBufferSource();
				this.source.connect(this.audioCtx.destination);
			}
			TaoAudio.prototype.load = function () {
				this.audioCtx.decodeAudioData(this.ab, function (buffer) {
					this.source.buffer = buffer;
					this.source.loop = this.loop;
					this.source.start(0);
				}.bind(this));
			}
			TaoAudio.prototype.play = function () {
				this.source.loop = this.loop;
				this.audioCtx.resume();
			}
			TaoAudio.prototype.pause = function () {
				this.audioCtx.suspend();
			}
			return new TaoAudio(a);
		},
		bgm: function (a, b) {
			function BGMControl(obj, tof) {
				this.switch = tof ? tof : true;
				this.btn = document.createElement("div");
				this.btn.className = "mbtn absolute";
				document.body.appendChild(this.btn);
				this.img = document.createElement("img");
				this.img.className = "w100 left";
				this.btn.appendChild(this.img);
				this.obj = obj;
				this.go = "data:image/png;base64,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";
				this.stop = "data:image/png;base64,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";
				this.set();
			}
			BGMControl.prototype.set = function () {
				var eventstart = "ontouchstart" in window ? "touchstart" : "mousedown";
				this.img.src = this.switch ? this.go : this.stop;
				this.btn.addEventListener(eventstart, function () {
					this.control();
				}.bind(this));
			}
			BGMControl.prototype.control = function () {
				if (this.switch) {
					this.obj.pause();
					this.img.src = this.stop;
					this.switch = false;
				} else {
					this.obj.play();
					this.img.src = this.go;
					this.switch = true;
				}
			}
			return new BGMControl(a, b);
		},
		ArraybufferToString: function (a) {
			var _ATS = function (e) {
				this.ab = e;
				return this.toString();
			}
			_ATS.prototype.toString = function () {
				var json = {
					"size": this.ab.byteLength,
					"arr": function () {
						var hold_arr = [];
						var read_dv = new DataView(this.ab);
						for (var i = 0; i < this.ab.byteLength; i++) {
							hold_arr.push(read_dv.getInt8(i));
						}
						return hold_arr;
					}.bind(this)()
				}
				return JSON.stringify(json);
			}
			return new _ATS(a);
		},
		StringToArraybuffer: function (a) {
			var _STA = function (e) {
				this.obj = JSON.parse(e);
				this.arr = this.obj.arr;
				this.size = this.obj.size;
				return this.toArraybuffer();
			}
			_STA.prototype.toArraybuffer = function () {
				var ab = new ArrayBuffer(this.size);
				var abdv = new DataView(ab);
				for (var i = 0; i < this.arr.length; i++) {
					abdv.setInt8(i, this.arr[i]);
				}
				return ab;
			}
			return new _STA(a);
		},
		console: function () {
			var _Console = function () {
				var styles = document.createElement("style");
				styles.innerHTML = 'console{position:fixed;width:100%;text-align:center;transition:all 0.5s;-webkit-transition:all 0.5s}console .scrollbody{height: 100px;overflow-x: hidden;overflow-y:auto}console p{margin: 5px;padding: 2px;border-bottom: 1px solid;}.console_btn{width: 10%;height: 15px;position: absolute;top: 100%;left: 45%;background-color: rgba(68, 68, 68, 0.5);}console div::-webkit-scrollbar{width:8px}console div::-webkit-scrollbar-corner{background:0 0}console div::-webkit-scrollbar-thumb{border:none;-webkit-border-radius:1ex;background-color:rgba(0,0,0,.4)}console{background-color:hsla(0,0%,100%,.2);box-shadow:0 0 10px rgba(0,0,0,.8)}.scrollbody div{position:relative;padding-left:8px;word-break: break-all;}dialog{position:fixed;z-index:9999;display:block;margin:0;padding:0;border:0;background-color:rgba(160,197,232,.3);}.dialog_info{display:inline-block;padding:5px;border:1px solid #fff;border-radius:5px;background-color:#000;color:#fff;}.dialog_name{display:inherit;padding:0 5px;border-right:1px solid #fff;color:pink;font-size:.8em;}.dialog_msg{display:inherit;padding:0 5px;font-size:.8em;}';
				var console = document.createElement("console");
				this.console_div = document.createElement("div");
				this.console_div.className = "scrollbody";
				this.console_body = document.createElement("div");
				this.console_div.appendChild(this.console_body);
				console.appendChild(this.console_div);
				document.body.appendChild(console);
				document.body.appendChild(styles);
				var tc = Extend.touch(this.console_div);
				tc.move = function (e) {
					this.console_div.scrollTop = this.console_div.scrollTop - e.moveY;
				}.bind(this);
				var swi = document.createElement("div");
				swi.className = "console_btn";
				swi.dataset.swi = "0";
				console.style.transform = "translateY(-102px)";
				console.style.webkitTransform = "translateY(-102px)";
				console.appendChild(swi);
				swi.addEventListener("touchstart", function () {
					var swi_btn = swi.dataset.swi;
					if (swi_btn == "1") {
						console.style.transform = "translateY(-102px)";
						console.style.webkitTransform = "translateY(-102px)";
						swi.dataset.swi = "0";
					} else {
						console.style.transform = "translateY(0px)";
						console.style.webkitTransform = "translateY(0px)";
						swi.dataset.swi = "1";
					}
				});
				/***************************************************************************************/
				this.dialog = document.createElement("dialog");
				this.dialog_info = document.createElement("div");
				this.dialog_info.className = "dialog_info";
				this.dialog_name = document.createElement("div");
				this.dialog_name.className = "dialog_name";
				this.dialog_msg = document.createElement("div");
				this.dialog_msg.className = "dialog_msg";
				this.dialog.appendChild(this.dialog_info);
				this.dialog_info.appendChild(this.dialog_name);
				this.dialog_info.appendChild(this.dialog_msg);
				document.body.appendChild(this.dialog);
				this.dialog_hidden();
				document.addEventListener("touchstart",function(e){
					var target = e.path[0];
					if(target == this.dialog || target == document.body){
						this.dialog_hidden();
					}else{
						this.dialog_show(target);
					}
				}.bind(this));
			}
			_Console.prototype.dialog_show = function (e) {
				this.dialog.style.display = "block";
				var target = e;
				var parent_scroll_top = target.offsetParent.scrollTop;
				var target_type = target.localName;
				var target_width = target.clientWidth;
				var target_height = target.clientHeight;
				var target_offsettop = target.offsetTop - parent_scroll_top;
				var target_offsetleft = target.offsetLeft;
				this.dialog.style.width = target_width + "px";
				this.dialog.style.height = target_height + "px";
				this.dialog.style.left = target_offsetleft + "px";
				this.dialog.style.top = target_offsettop + "px";
				this.dialog_name.innerHTML = target_type;
				this.dialog_msg.innerHTML = target_width + "×" + target_height;
			}
			_Console.prototype.dialog_hidden = function () {
				this.dialog.style.display = "none";
			}
			_Console.prototype.log = function (e) {
				var p = document.createElement("p");
				p.innerHTML = e;
				this.console_body.appendChild(p);
			}
			return new _Console();
		}
	}
	Tao.prototype.init = function () {
		if (this.setobj.extend) {
			String.prototype.checklength = function () {
				var char = this.replace(/[^\x00-\xff]/g, 'xx');
				return char.length;
			}
			String.prototype.cutstr = function (len, suffix) {
				if (!suffix) suffix = "";
				if (len <= 0) return "";
				if (this.checklength == 0) return "";
				var templen = 0;
				for (var i = 0; i < this.length; i++) {
					if (this.charCodeAt(i) > 255) {
						templen += 2;
					} else {
						templen++
					}
					if (templen > len) {
						break;
					}
				}
				if (templen > len) {
					this.becut = this.substring(0, i) + suffix;
					return this.becut;
				}
				return this;
			}
			String.prototype.insecret = function () {
				var str1 = "";
				for (var i = 0; i < this.length; i++) {
					var lz = this[i].charCodeAt(0);
					lz = lz <= 255 ? "00" + lz.toString(16) : lz <= 4095 ? "0" + lz.toString(16) : lz.toString(16);
					str1 = str1 + "\\u" + lz;
				}
				return str1;
			}
			String.prototype.unsecret = function () {
				var str1 = this;
				str1 = str1.replace(/(%5C)/g, "\\");
				str1 = eval("'" + str1 + "'");
				return str1;
			}
			String.prototype.phone = function () {
				var partten = /^1[3,5,8]\d{9}$/
				return partten.test(this) ? true : false;
			}
			String.prototype.assign = function (e) {
				var objlist = document.querySelectorAll(e);
				for (var i = 0; i <= objlist; i++) {
					var obj = objlist[i];
					if (obj.nodeName == "img" || obj.nodeName == "IMG") {
						obj.src = this;
					} else {
						obj.style.backgroundImage = "url(" + this + ")";
					}
				}
			}
			String.prototype.none = function () {
				var objlist = document.querySelectorAll(this);
				for (var i = 0; i < objlist.length; i++) {
					var obj = objlist[i];
					if (!hasClass(obj, "none")) obj.className += " " + "none";
				}
			}
			String.prototype.block = function () {
				var objlist = document.querySelectorAll(this);
				for (var i = 0; i < objlist.length; i++) {
					var obj = objlist[i];
					if (hasClass(obj, "none")) {
						var reg = new RegExp('(\\s|^)' + 'none' + '(\\s|$)');
						obj.className = obj.className.replace(reg, ' ');
					}
				}
			}
			Blob.prototype.url = function () {
				return URL.createObjectURL(this);
			}
			Blob.prototype.BlobToArrayBuffer = function () {
				return new Promise(function (a, b) {
					var bta_fr = new FileReader();
					bta_fr.onload = function () {
						a(bta_fr.result);
					}
					bta_fr.readAsArrayBuffer(this);
				}.bind(this));
			}
		}
		if (this.setobj.orient && this.setobj.orient != "none") {
			this.extend.orient(this.setobj.orient);
		}
		if (this.setobj.load) {
			if (localStorage.getItem(this.setobj.storage)) {
				var ab = this.extend.StringToArraybuffer(localStorage.getItem(this.setobj.storage));
				this.result({
					load_onloaded: ab
				});
				if (this.setobj.decode) {
					var decode = this.extend.decode(ab);
					decode.onloaded = function (x) {
						this.result({
							decode: x
						});
					}.bind(this)
				}
			} else {
				var load = this.extend.load(this.setobj.load);
				load.onprogress = function (e) {
					this.result({
						load_onprogress: e
					});
				}.bind(this)
				load.onloaded = function (e) {
					this.result({
						load_onloaded: e
					});
					if (this.setobj.storage) {
						var ATS = this.extend.ArraybufferToString(e);
						localStorage.setItem(this.setobj.storage, ATS);
					}
					if (this.setobj.decode) {
						var decode = this.extend.decode(e);
						decode.onloaded = function (x) {
							this.result({
								decode: x
							});
						}.bind(this)
					}
				}.bind(this)
			}
		}
		if (this.setobj.console) {
			window.console = this.extend.console();
		}
	}
	Tao.prototype.result = function (e) {}
	Tao.prototype.Count = function (num) {
		return this.extend.count(num);
	}
	Tao.prototype.Touch = function (obj) {
		return this.extend.touch(obj);
	}
	Tao.prototype.Shake = function () {
		return this.extend.shake();
	}
	Tao.prototype.TaoVideo = function (a, b, c) {
		return this.extend.taovideo(a, b, c);
	}
	Tao.prototype.TaoAudio = function (a) {
		return this.extend.taoaudio(a);
	}
	Tao.prototype.BGMControl = function (a, b) {
		return this.extend.bgm(a, b);
	}
	if (global) {
		window.Tao = Tao;
	}
	(function (e) {
		if (typeof (Map) != "function") {
			e.Map = function (arr) {
				this.size;
				this.key = [];
				this.value = [];
				if (arr) {
					arr.forEach(function (a) {
						this.key.push(a[0]);
						this.value.push(a[1]);
					});
				}
			}
			Map.prototype.set = function (a, b) {
				var index = this.key.indexOf(a);
				if (index == -1) {
					this.key.push(a);
					this.value.push(b);
				} else {
					this.value[index] = b;
				}
			}
			Map.prototype.get = function (a) {
				var index = this.key.indexOf(a);
				if (index == -1) {
					return undefined;
				} else {
					return this.value[index];
				}
			}
			Map.prototype.forEach = function (callback) {
				for (var i = 0; i < this.key.length; i++) {
					callback(this.value[i], this.key[i], [this.key[i], this.value[i]]);
				}
			}
			Map.prototype.has = function (a) {
				var index = this.key.indexOf(a);
				return index == -1 ? false : true;
			}
			Map.prototype.values = function () {
				return new MapIterator(this.value);
			}
			Map.prototype.keys = function () {
				return new MapIterator(this.key);
			}

			function MapIterator(a) {
				this.IteratorIndex = 0;
				this.Entries = [];
				a.forEach(function (b) {
					this.Entries.push({
						value: b
					});
				}.bind(this));
			}
			MapIterator.prototype.next = function () {
				var re = this.Entries[this.IteratorIndex];
				this.IteratorIndex++;
				return re;
			}
		}

		e.$http = function (url) {
			var core = {
				ajax: function (method, url, args) {
					var promise = new Promise(function (resolve, reject) {
						var client = new XMLHttpRequest();
						var uri = url;
						client.responseType = "json";
						if (args && (method === 'GET')) {
							uri += '?';
							var argcount = 0;
							for (var key in args) {
								if (args.hasOwnProperty(key)) {
									if (argcount++) {
										uri += '&';
									}
									uri += encodeURIComponent(key) + '=' + encodeURIComponent(args[key]);
								}
							}
							client.open(method, uri);
							client.send();
						} else if (args && (method === 'POST' || method === 'PUT')) {
							var post_url = '';
							var argcount = 0;
							for (var key in args) {
								if (args.hasOwnProperty(key)) {
									if (argcount++) {
										post_url += '&';
									}
									post_url += encodeURIComponent(key) + '=' + encodeURIComponent(args[key]);
								}
							}
							client.open(method, uri);
							client.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
							client.send(post_url);
						} else {
							client.open(method, uri);
							client.send();
						}

						client.onload = function () {
							if (this.status >= 200 && this.status < 300) {
								resolve(this.response);
							} else {
								reject(this.statusText);
							}
						}
						client.onerror = function () {
							reject(this.statusText);
						}
					});
					return promise;
				}
			}
			return {
				'get': function (args) {
					return core.ajax('GET', url, args);
				},
				'post': function (args) {
					return core.ajax('POST', url, args);
				},
				'put': function (args) {
					return core.ajax('PUT', url, args);
				},
				'delete': function (args) {
					return core.ajax('DELETE', url, args);
				}
			}
		}
		e.hasClass = function (obj, cls) {
			return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
		}
		e.binayUtf8ToString = function (buf, begin) {
			var i = 0;
			var pos = 0;
			var str = "";
			var unicode = 0;
			var flag = 0;
			for (pos = begin; pos < buf.length;) {
				flag = buf[pos];
				if ((flag >>> 7) === 0) {
					str += String.fromCharCode(buf[pos]);
					pos += 1;

				} else if ((flag & 0xFC) === 0xFC) {
					unicode = (buf[pos] & 0x3) << 30;
					unicode |= (buf[pos + 1] & 0x3F) << 24;
					unicode |= (buf[pos + 2] & 0x3F) << 18;
					unicode |= (buf[pos + 3] & 0x3F) << 12;
					unicode |= (buf[pos + 4] & 0x3F) << 6;
					unicode |= (buf[pos + 5] & 0x3F);
					str += String.fromCharCode(unicode);
					pos += 6;

				} else if ((flag & 0xF8) === 0xF8) {
					unicode = (buf[pos] & 0x7) << 24;
					unicode |= (buf[pos + 1] & 0x3F) << 18;
					unicode |= (buf[pos + 2] & 0x3F) << 12;
					unicode |= (buf[pos + 3] & 0x3F) << 6;
					unicode |= (buf[pos + 4] & 0x3F);
					str += String.fromCharCode(unicode);
					pos += 5;

				} else if ((flag & 0xF0) === 0xF0) {
					unicode = (buf[pos] & 0xF) << 18;
					unicode |= (buf[pos + 1] & 0x3F) << 12;
					unicode |= (buf[pos + 2] & 0x3F) << 6;
					unicode |= (buf[pos + 3] & 0x3F);
					str += String.fromCharCode(unicode);
					pos += 4;

				} else if ((flag & 0xE0) === 0xE0) {
					unicode = (buf[pos] & 0x1F) << 12;;
					unicode |= (buf[pos + 1] & 0x3F) << 6;
					unicode |= (buf[pos + 2] & 0x3F);
					str += String.fromCharCode(unicode);
					pos += 3;

				} else if ((flag & 0xC0) === 0xC0) { //110
					unicode = (buf[pos] & 0x3F) << 6;
					unicode |= (buf[pos + 1] & 0x3F);
					str += String.fromCharCode(unicode);
					pos += 2;

				} else {
					str += String.fromCharCode(buf[pos]);
					pos += 1;
				}
			}
			return str;

		}
	})(window)
})(true);
