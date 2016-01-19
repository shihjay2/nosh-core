(function($){
    $.timeCube = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("timeCube", base);

		// Initialization
        base.init = function(){
        	// merge in any specified options
            base.options = $.extend({},$.timeCube.defaultOptions, options);   
       
			// the width of the timeline;
			base.timelineWidth = base.$el.width();

			// which event is selected
			base.currentIndex = 0;

       		// Set our dates to Unix epoch
        	if(base.options.startDate == "default"){
        		base.startDate = new Date(base.options.data[0].startDate);
        	} else {
        		base.startDate = new Date(base.options.startDate);
        	}
        	
        	if(base.options.endDate == "default"){
        		base.endDate = new Date(base.options.data[(base.options.data.length - 1)].startDate);
        	} else {
        		base.endDate = new Date(base.options.endDate);
        	}

        	// master CSS class
        	base.$el.addClass('timeCube');
        	
        	// Create top nav
        	base.$nav = $("<div>");
        	base.$nav.addClass('nav');
        	base.$el.append(base.$nav);

        	// the legend
        	base.$legend = $("<div>");
        	base.$legend.addClass('legend');
        	base.$nav.append(base.$legend);
        	
        	// the line
        	base.$line = $("<div>");
        	base.$line.addClass('line');
        	base.$nav.append(base.$line);
        	        	
        	// Create container to hold all of the timeline objects
        	base.$container = $("<div>");
        	base.$container.addClass('container');
        	base.$el.append(base.$container);
        	
 
 
         	// Define month friendly names
        	var monthNames=new Array(12);
			monthNames[0]="JANUARY";
			monthNames[1]="FEBRUARY";
			monthNames[2]="MARCH";
			monthNames[3]="APRIL";
			monthNames[4]="MAY";
			monthNames[5]="JUNE";
			monthNames[6]="JULY";
			monthNames[7]="AUGUST";
			monthNames[8]="SEPTEMBER";
			monthNames[9]="OCTOBER";
			monthNames[10]="NOVEMBER";
			monthNames[11]="DECEMBER";
 
 
 
        	
        	// loop through the timeline data and add them to the page
        	base.eventDivs = [];
        	base.markerDivs = [];
        	$(base.options.data).each(function(index, d){
        	
        		// First, the big timeline divs
				base.eventDivs[index] = $("<div>");
	        	base.eventDivs[index].addClass('event');
	        	
				if(base.options.showDate){
					base.eventDivs[index].html("<span class='date'>" + monthNames[d.startDate.getUTCMonth()] + " " + d.startDate.getUTCDate() + ", " + d.startDate.getUTCFullYear() + "</span><h1>" + d.title + "</h1><p>" + d.description + "</p>");
				} else {
					base.eventDivs[index].html("<h1>" + d.title + "</h1><p>" + d.description + "</p>");
				}
				
                if(d.extraclass){
                base.eventDivs[index].addClass(d.extraclass);
                };
                
				base.$container.append(base.eventDivs[index]);
	        	base.eventDivs[index].css("left", (base.timelineWidth * index) + "px");

	        	
	        	// Second, the markers along the top
				base.markerDivs[index] = $("<div>");
	        	base.markerDivs[index].addClass('marker');
	        	base.markerDivs[index].css("left", base.getNavPosition(d.startDate) + "px");
	        	base.$line.append(base.markerDivs[index]);	        		       	
        	});
        	
        	// ******************************
        	// Build the legend
        	// ******************************

        	
        	
        	// set legendCount to the number of items we should be drawing
        	if(base.options.granularity == "century"){
				var legendCount = Math.floor(base.yearsBetweenDates(base.startDate, base.endDate) / 100);
			} else if(base.options.granularity == "decade"){
				var legendCount = Math.floor(base.yearsBetweenDates(base.startDate, base.endDate) / 10);
			} else if(base.options.granularity == "year"){
				var legendCount = base.yearsBetweenDates(base.startDate, base.endDate);
			} else if(base.options.granularity == "month"){
				var legendCount = base.monthsBetweenDates(base.startDate, base.endDate);
			} else if(base.options.granularity == "day"){
				var legendCount = base.daysBetweenDates(base.startDate, base.endDate);
			} else {
				alert('invalid setting for granularity');
			}


			// then draw all of the legend items
        	for (i=0;i<=legendCount;i++){
   				
   				// We'll start with a date of 0 and set it to match whichever legend element we are currently showing
   				var m = new Date(0);
   				
   				// we
   				var label = "undefined";
   				



   				if(base.options.granularity == "century"){
   					var newYear = Math.floor((base.startDate.getUTCFullYear() + (i * 100)) / 100) * 100;
					m.setUTCFullYear(newYear); // increment year on each loop
					label = m.getUTCFullYear() + "s";
				} else if(base.options.granularity == "decade"){
					var newYear = Math.floor((base.startDate.getUTCFullYear() + (i * 10)) / 10) * 10;
					m.setUTCFullYear(newYear); // increment year on each loop
					label = m.getUTCFullYear();
				} else if(base.options.granularity == "year"){
					m.setUTCFullYear(base.startDate.getUTCFullYear() + i); // increment year on each loop
					label = m.getUTCFullYear();
				} else if(base.options.granularity == "month"){
					m.setUTCFullYear(base.startDate.getUTCFullYear());				
					m.setUTCMonth(base.startDate.getUTCMonth() + i); // increment month on each loop
					label = monthNames[m.getUTCMonth()] + " " + m.getUTCFullYear();		
				} else if(base.options.granularity == "day"){
					m.setUTCFullYear(base.startDate.getUTCFullYear());				
					m.setUTCMonth(base.startDate.getUTCMonth());				
					m.setUTCDate(base.startDate.getUTCDate() + i); // increment day on each loop
					label = m.getUTCDate() == 1 ? (m.getUTCDate() + " " + monthNames[m.getUTCMonth()]) : m.getUTCDate();			
				} else {
					alert('invalid setting for granularity');
					return false;
				}
   								
				// base.getNavPosition converts a date to a pixel X position on the timeline nav. If it's below 0, we just want to show the marker at 0.
				var pixelPosition = base.getNavPosition(m) < 0 ? 0 : base.getNavPosition(m);
				
				// Then create the label element
				var labelDiv = $("<div>");
	        	labelDiv.addClass('label');
	        	labelDiv.html(label);
	        	labelDiv.css("left", pixelPosition + "px"); // all other positioning is handled in CSS
	        	base.$legend.append(labelDiv);	
			}
			
        
        
        
        
	        // *********************************************************************************************
			// EVENTS
			// *********************************************************************************************
			
			
			// We'll store mouse info here while interactions are in progress. (or touches).
			base.mouse = {
				start: {},
				last: {},
				momentum: 0
			};
			base.containerPosition = {
				current: 0,
				start: 0
			};

			// is this a touch device?
			base.touch = document.ontouchmove !== undefined;
			
			base.$container.bind((base.touch ? 'touchstart' : 'mousedown touchstart'), function(evt) {
			
				

				// disable CSS animations				
				base.$container.css({
					'-webkit-transition-property': '-webkit-transform',
					'-webkit-transition-duration': '0ms'
			    });
				
				$(base.eventDivs).each(function(i, d){
					$(d).css({
						'-webkit-transition-property': '-webkit-transform',
						'-webkit-transition-duration': '0ms'				
					});
					$(d).children().css({
						'-webkit-transition-property': '-webkit-transform',
						'-webkit-transition-duration': '0ms'				
					});
				});					
				
				
				base.containerPosition.start = base.containerPosition.current;
				
				
				
				// allow clicks on links within the moveable area
				if($(evt.target).is('a, iframe')) {
					return true;
				}
			
				evt.originalEvent.touches ? evt = evt.originalEvent.touches[0] : null;
				base.mouse.start.x = evt.pageX;
				base.mouse.start.y = evt.pageY;
				base.mouse.startEvent = base.mouse.currentEvent;
				
				base.mouse.last.x = event.pageX;
				base.mouse.last.y = event.pageY;
				base.mouse.last.time = new Date().getTime();
				
				$(document).bind('mousemove touchmove', function(event) {
					// Only perform movement if one touch or mouse
					if(!base.touch || !(event.originalEvent && event.originalEvent.touches.length > 1)) {
						event.preventDefault();
						// Get touch co-ords
						event.originalEvent.touches ? event = event.originalEvent.touches[0] : null;
						// DO SOMETHING HERE
						
						
						// get the momentum
						var moveTime = (new Date().getTime() - base.mouse.last.time);
						var moveDistance = base.mouse.last.x - event.pageX;
						
						base.mouse.momentum = moveDistance / moveTime * -100;
						
						
						
						
						// track the last position and time for momentum
						base.mouse.last.x = event.pageX;
						base.mouse.last.y = event.pageY;
						base.mouse.last.time = new Date().getTime();
						
						
						base.containerPosition.current = base.containerPosition.start + event.pageX - base.mouse.start.x;
						
						// make sure we stay within the timeline items
						var minX = (-1 * (base.options.data.length - 1) * base.timelineWidth);
						var maxX = 0;
						if(base.containerPosition.current > maxX){
							base.containerPosition.current = base.containerPosition.current / 3;
						}
						
						if(base.containerPosition.current < minX){
							base.containerPosition.current = ((base.containerPosition.current - minX) / 3) + minX;
						}											
						base.$container[0].style.webkitTransform = "translateX(" + base.containerPosition.current + "px)";
						
						base.doTransitions();
				
						
						
						
					}			
				});	
				
				$(document).bind('mouseup touchend', function (e) {
					$(document).unbind('mousemove touchmove mouseup touchend');
					// SNAP
					
					
					
					// if the last move was less than 50 miliseconds ago, use momentum
					var moveTime = (new Date().getTime() - base.mouse.last.time);
					if(moveTime < 50){
					    base.containerPosition.current += base.mouse.momentum;				
					}
					
					
					// find out to which index we should be snapping
					var closestEventIndex = Math.round(base.containerPosition.current / base.timelineWidth) * -1;
					closestEventIndex = closestEventIndex < 0 ? 0 : closestEventIndex;
					closestEventIndex = closestEventIndex > (base.options.data.length - 1) ? (base.options.data.length - 1) : closestEventIndex;
					base.snapToIndex(closestEventIndex);
					
				});
			});


			// touch the navigation bar
			base.$nav.bind((base.touch ? 'touchstart' : 'mousedown touchstart'), function(evt) {
				evt.preventDefault();
			
				evt.originalEvent.touches ? evt = evt.originalEvent.touches[0] : null;
				base.mouse.start.x = evt.pageX;
				base.mouse.start.y = evt.pageY;
				base.mouse.startEvent = base.mouse.currentEvent;
				base.navTouch(evt);
				
				$(document).bind('mousemove touchmove', function(event) {
					// Only perform movement if one touch or mouse
					if(!base.touch || !(event.originalEvent && event.originalEvent.touches.length > 1)) {
						event.preventDefault();
						// Get touch co-ords
						event.originalEvent.touches ? event = event.originalEvent.touches[0] : null;
						base.navTouch(event);
						
					}			
				});	
				
				$(document).bind('mouseup touchend', function (e) {
					$(document).unbind('mousemove touchmove mouseup touchend');

					
				});
			});
		








			// NEXT BUTTON
			if(base.options.nextButton != undefined){
				$(base.options.nextButton).bind((base.touch ? 'touchstart' : 'mousedown touchstart'), function(evt) {
					evt.preventDefault();
					if(base.currentIndex < (base.options.data.length - 1)){
						$(base.options.nextButton).addClass('active');
						base.snapToIndex(base.currentIndex + 1);
					}
				});
				
				$(base.options.nextButton).bind((base.touch ? 'touchend' : 'mouseup touchend'), function(evt) {
					evt.preventDefault();
					$(base.options.nextButton).removeClass('active');
				});					
			}
							
			
			
			// PREVIOUS BUTTON
			if(base.options.previousButton != undefined){
				$(base.options.previousButton).bind((base.touch ? 'touchstart' : 'mousedown touchstart'), function(evt) {
					evt.preventDefault();
					if(base.currentIndex > 0){
						$(base.options.previousButton).addClass('active');
						base.snapToIndex(base.currentIndex - 1);
					}
					return false;
				});
			
				$(base.options.previousButton).bind((base.touch ? 'touchend' : 'mouseup touchend'), function(evt) {
					evt.preventDefault();
					$(base.options.previousButton).removeClass('active');
				});						
			}






			// Snap to the first timeline event
			base.snapToIndex(0);







        };






        
		// *********************************************************************************************
		// FUNCTIONS
		// *********************************************************************************************





		// *********************************************************************************************
		// base.navTouch(event)
		// Handles events from the nav bar
		// *********************************************************************************************
		base.navTouch = function(event){

			// DO SOMETHING HERE				
			var navX = event.pageX - base.$nav.offset().left - parseInt(base.$nav.css("padding-left").replace("px", ""));
			var navY = event.pageY - base.$nav.offset().top;
			
			// get the multiplier for how far into the timeline we just touched
			var timelinePosition = navX / base.$nav.width();
			
			// and generate a date that would correspond
			var targetTime = Math.floor(((base.endDate.getTime() - base.startDate.getTime()) * timelinePosition) + base.startDate.getTime());												
	
			var timeDifferences = [];
			// now to find the closest match...
			$(base.options.data).each(function(i, d){
				timeDifferences[i] = Math.abs(targetTime - d.startDate.getTime());
			});
			var index = timeDifferences.indexOf(Math.min.apply(null, timeDifferences));						
			if(index != base.currentIndex){
				base.snapToIndex(index);
			}
							
		};





		// *********************************************************************************************
		// base.doTransitions()
		// Styles all the event divs with their current transform for transitions
		// *********************************************************************************************
		base.doTransitions = function(){
			base.$container.children().each(function(index, d){
				var distanceFromCenter = base.containerPosition.current - (index * base.timelineWidth * -1); // how far (+/-) they are from position 0
				var transitionPoint = distanceFromCenter / base.timelineWidth; // find the fraction
				// transitionPoint < -1 ? transitionPoint = -1 : null; //cap at 1 / -1
				// transitionPoint > 1 ? transitionPoint = 1 : null; // cap at 1 / -1
				d.style.webkitTransform = "translate3D(" + (base.options.transitionSpacing * transitionPoint * -1) + "px, 0px, " + (Math.abs(transitionPoint) * -1 * (base.timelineWidth / 2)) + "px) rotateY(" + (base.options.transitionAngle * transitionPoint) + "deg)";				
				
				/*
				// animate each element independently
				var childTransitionStrength = (0.5 - Math.abs(0.5 - Math.abs(transitionPoint))) * 2;
				transitionPoint < 0 ? (childTransitionStrength = childTransitionStrength * -1) : null;
				
				$(d).children().each(function(i, c){
					c.style.webkitTransform = "translate3D(0px, 0px, 0px) rotateY(" + (childTransitionStrength * i * 8) + "deg)";
				});
				*/
			
			});								
		};


		// *********************************************************************************************
		// base.snapToIndex(index)
		// return an x position along the timeline nav for a given date, based on base.$nav.width();
		// *********************************************************************************************
		base.snapToIndex = function(index){
		
			base.currentIndex = index;
		
			// turn on CSS animations
			base.$container.css({
				'-webkit-transition-property': '-webkit-transform',
				'-webkit-transition-duration': '400ms'
		    });
		    
		    // set the current position and move the timeline container
			base.containerPosition.current = index * base.timelineWidth * -1
			base.$container[0].style.webkitTransform = "translateX(" + base.containerPosition.current + "px)";
			
			
			// turn on CSS animations for events and calculate transitions
			$(base.eventDivs).each(function(i, d){
				$(d).css({
					'-webkit-transition-property': '-webkit-transform',
					'-webkit-transition-duration': '400ms'				
				});
				$(d).children().css({
					'-webkit-transition-property': '-webkit-transform',
					'-webkit-transition-duration': '400ms'				
				});			
			});			
			base.doTransitions();
						
			// Activate the marker
			$(base.markerDivs).each(function(i, m){
				m.removeClass('active');
			});
			$(base.markerDivs[index]).addClass('active');	
			
			
			// enable or disable previous & next controls
			if(base.options.previousButton != undefined && base.currentIndex == 0){
				$(base.options.previousButton).addClass("disabled");
			} else {
				$(base.options.previousButton).removeClass("disabled");			
			}
			
			if(base.options.nextButton != undefined && base.currentIndex >= (base.options.data.length - 1)){
				$(base.options.nextButton).addClass("disabled");
			} else {
				$(base.options.nextButton).removeClass("disabled");			
			}			
			
		};




		// *********************************************************************************************
		// base.getNavPosition(date)
		// return an x position along the timeline nav for a given date, based on base.$nav.width();
		// *********************************************************************************************
		base.getNavPosition = function(date){
		
			var timelineStartTime = base.startDate.getTime();
			var timelineEndTime = base.endDate.getTime();
			var eventTime = date.getTime();
			var range = base.endDate.getTime() - base.startDate.getTime();
			utcDate = date.getTime();
			utcDate -= base.startDate.getTime();
			var position = utcDate / range;
			var pixelPosition = position * base.$nav.width();
			var testPosition = (eventTime - timelineStartTime) / (timelineEndTime - timelineStartTime); 
			return Math.floor(pixelPosition);
		};

		// *********************************************************************************************
		// base.yearsBetweenDates(date1, date2)
		// Returns the number of years between two dates
		// *********************************************************************************************
		base.yearsBetweenDates = function(date1, date2){
			var yearGap = Math.abs(date2.getUTCFullYear() - date1.getUTCFullYear());
			return yearGap;
		};


		// *********************************************************************************************
		// base.monthsBetweenDates(date1, date2)
		// Returns the number of months between two dates
		// *********************************************************************************************
		base.monthsBetweenDates = function(date1, date2){
			var monthGap = Math.abs(date2.getUTCMonth() - date1.getUTCMonth());
			if(base.startDate.getUTCFullYear() < base.endDate.getUTCFullYear()){
				monthGap += (Math.abs(base.endDate.getUTCFullYear() - base.startDate.getUTCFullYear()) * 12);
			}
			return monthGap;
		};
		
		// *********************************************************************************************
		// base.daysBetweenDates(date1, date2)
		// Returns the number of days between two dates
		// *********************************************************************************************		
		base.daysBetweenDates = function(date1, date2){
		
		    // The number of milliseconds in one day
		    var ONE_DAY = 1000 * 60 * 60 * 24
		
		    // Convert both dates to milliseconds
		    var date1_ms = date1.getTime()
		    var date2_ms = date2.getTime()
		
		    // Calculate the difference in milliseconds
		    var difference_ms = Math.abs(date1_ms - date2_ms)
		    
		    // Convert back to days and return
		    return Math.round(difference_ms/ONE_DAY)
		
		};		
	
		

        // Run initializer
        base.init();
    };


	// Default options
    $.timeCube.defaultOptions = {
        data: {},
        granularity: "month",
        startDate: "default",
        endDate: "default",
        transitionAngle: 60,
        transitionSpacing: 100,
        nextButton: undefined,
        previousButton: undefined,
		showDate: true
    };

    $.fn.timeCube = function(options){
        return this.each(function(){
            (new $.timeCube(this, options));

					
                   // HAVE YOUR PLUGIN DO GENERIC STUFF HERE

                   // END DOING STUFF

        });
    };

})(jQuery);