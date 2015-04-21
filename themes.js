(function($){
	$(document).ready(function(){			   
var themes=[
["#eeeeee","#99aabb","#8899aa"],
["#eeeeee","#aa99bb","#9988aa"],
["#400510","#8C1B2F","#A69692"],
["#3B3C40","#60A685","#F2DDD0"],
["#2B2240","#F2D1B3","#8C4E18"],
["#8C4E18","#A66844","#401910"],
["#F2F2F2","#260F1A","#0D0C0D"],
["#AAF1FE","#80848B","#805950"],
["#3264A6","#6F92BF","#8FA6C9"],
["#8C323A","#F2DEC4","#BF8C6F"],
["#D9B91A","#8C770F","#8C7223"],
["#000000","#166FBA","#825840"],
["#A6806A","#F25C5C","#BF4B4B"],
["#D9BCA3","#736055","#A6806A"],
["#F2C2CB","#A65168","#F19A9E"],
["#F19A9E","#8C6E64","#F2EAE9"],

["#1B1821","#BFB796","#D3C0AC"],
["#BCA395","#DECBC3","#D3C0AC"],

	   
];

		
			$.each(themes, function(key,val){
				$(document).create_themes(val);		
			});			
		
		
	});
})(jQuery);