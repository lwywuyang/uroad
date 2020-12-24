


var data = [
	{
		"name":"name1",
		 "station":["chongqinggaosu","chongqinggaosu","chongqinggaosu","chongqinggaosu"]
	},
	{
		"name":"name",
		 "station":"chongqinggaosu2",
	}
];


function service(){
	var data = [
	{
		"name":"name1",
		 "station":["chongqinggaosu","chongqinggaosu","chongqinggaosu","chongqinggaosu"]
	},
	{
		"name":"name",
		 "station":"chongqinggaosu2",
	}
];	
	var content = "";
	var str = '<li role="presentation" ><a href="#">Home</a></li>';
	var str2 = '<div class="element">test</div>';

	for(var i = 0; i < data.length;i++){
		content += str;
	}
	var ul = document.getElementsByTagName("ul")[0];
	ul.innerHTML = content;
	for(var i = 0 ;i<data.length; i++){
		var a = document.getElementsByTagName("li")[i].firstChild.firstChild;
		a.nodeValue = data[i].name;
		
		
		a.onClick() = function(){

		};

	}



	var content1 = "";






	var str2 = '<div class="element">test</div>';
	// console.log(data[0].station.length);


	for(var i=0; i<data[0].station.length;i++){
		content1 += str2;
	}
	var div = document.getElementsByClassName("col-xs-8")[0];
	div.innerHTML = content1;



}