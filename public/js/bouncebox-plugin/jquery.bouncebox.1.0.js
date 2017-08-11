(function($){
	
	/* The plugin extends the jQuery Core with four methods */
	
	/* Converting an element into a bounce box: */
	$.fn.bounceBox = function(){
		
		/*
			Applying some CSS rules that center the
			element in the middle of the page and
			move it above the view area of the browser.
		*/
		
		this.css({
			top			: -this.outerHeight(),
			marginLeft	: -this.outerWidth()/2,
			position	: 'absolute',
			left		: '50%'
		});
		
		return this;
	}
	
	/* The boxShow method */
	$.fn.bounceBoxShow = function(){
		
		/* Starting a downward animation */
		this.css("display", "collapse");
		this.stop().animate({top:0},{/*easing:'easeOutBounce'*/});
		this.data('bounceShown',true);
		this.css("display", "block"); //MEJORA: Anexado por Miguel Salazar, 21/02/2016
		return this;
	}
	
	/* The boxHide method */
	$.fn.bounceBoxHide = function(){
		
		/* Starting an upward animation */
		this.css("display", "none"); //MEJORA: Anexado por Miguel Salazar, 21/02/2016
		this.stop().animate({top:-this.outerHeight()});
		this.data('bounceShown',false);
		return this;
	}
	
	/* And the boxToggle method */
	$.fn.bounceBoxToggle = function(){
		
		/* 
			Show or hide the bounceBox depending
			on the 'bounceShown' data variable
		*/
		
		if(this.data('bounceShown'))
			this.bounceBoxHide();
		else {
			
			this.bounceBoxShow();
		}
		
		
		return this;
	}
	
})(jQuery);