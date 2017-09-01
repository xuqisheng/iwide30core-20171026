new Orient();
var swi = true;
document.addEventListener("touchmove",function(e){
    if(swi) e.preventDefault();
});
var btn = document.querySelector(".btn");
var login = document.querySelector(".login");
var p2 = document.querySelector(".p2");
btn.addEventListener("touchstart",function(){
    nextpage();
});
function nextpage(){
	".bg".block();
    swi = false;
    document.body.style.overflow = "auto";
    login.className = "login absolute w100 flipOutX";
    login.addEventListener("webkitAnimationEnd",function(){
    	login.className = "login absolute w100 none";
    });
}
function shownum(a,b){
	this.num_old = a;
	this.num_new = b;
	this.add();
}
shownum.prototype.add = function(){
	this.show()
	this.num_old = this.num_old + 1;
	if(this.num_old <= this.num_new){
		window.requestAnimationFrame(function(){
			this.add();
		}.bind(this));
	}
}
shownum.prototype.show = function(){
	var str = this.num_old.formatMoney(0,"");
	p2.innerText = str;
}
Number.prototype.formatMoney = function (places, symbol, thousand, decimal) {
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : "$";
    thousand = thousand || ",";
    decimal = decimal || ".";
    var number = this,
        negative = number < 0 ? "-" : "",
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};