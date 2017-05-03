<!-----------------------------------------------------------------------------------------------------------
--- *** SCRIPTS ***
------------------------------------------------------------------------------------------------------------>


<!-- Expand results for detailed content (toggle show/hide switches) -->

<script type="text/javascript">
$(document).ready(function(){
	$("#hide").hide();
	$("#expand_delimiter").hide();
	$("#hide").click(function(){
		$(".contentfull").hide();
		$("#hide").hide();
		$("#show").show();
		$(".expand_hide").hide();
		$(".expand_show").show();
		$("#expand_delimiter").hide();
	});
		$("#show").click(function(){
			$(".contentfull").show();
			$("#hide").show();
			$("#show").hide();
			$(".expand_hide").show();
			$(".expand_show").hide();
			$("#expand_delimiter").hide();
		});
});
</script>

<!-- Clear search box -->

<script type="text/javascript">
$('.has-clear input[type="text"]').on('input propertychange', function() {
	var $this = $(this);
	var visible = Boolean($this.val());
	$this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$('.form-control-clear').click(function() {
	$(this).siblings('input[type="text"]').val('')
	.trigger('propertychange').focus();
});
</script>


<!-- IE - search box - prevent cursor jumping to beginning of text -->

<script type="text/javascript">
var el = $("#inputSearchString");
el[0].onfocus = el[0].onblur = null;
$(el).on("focus blur", function(e) {
	this.value = $.trim(this.value);
	if ((e.type === "focus") && this.createTextRange) {
		var r = this.createTextRange();
		r.moveStart("character", this.value.length);
		r.select();
	}
});
</script>


<!-- Search Box Autocomplete (powered by Wikipedia) -->

<script type="text/javascript">
 $("#inputSearchString").autocomplete({ 
	source: function(request, response) { 
		$.ajax({ 
			url: "http://en.wikipedia.org/w/api.php", 
			dataType: "jsonp",
			data: { 
				'action': "opensearch", 
				'format': "json", 
				'search': request.term  
			}, 

			success: function(data) { 
				response(data[1]); 
			} 
		}); 
	} 
}); 
</script>


<!-- Don't submit empty inputs to GET-query -->

<script type="text/javascript">
$('body').submit(function(e){
    var emptyinputs = $(this).find('input').filter(function(){
        return !$.trim(this.value).length;  // get all empty fields
    }).prop('disabled',true);    
});

</script>

<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-80866076-2', 'auto');
	ga('require', 'linkid');
	//ga('send', 'pageview');

</script>

<div class="push"></div>
</div><!-- ./wrapper-->
<div id="footerCopyright"></div>
</body>
</html>