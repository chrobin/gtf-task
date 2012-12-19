(function($) {
	$.fn.editor = function(options) {
		function show($this) {
			var type = $this.data('editor');
			var text = $this.html().trim();
			switch (type) {
				case 'text':
					$this.html("<input type='text' value='" + text + "' />");
					$this.data('editing', true);
					break;
				case 'textarea':
					$this.html("<textarea>" + text + "</textarea>");
					$this.data('editing', true);
					break;
			}
		}
		function hide($this) {
			var type = $this.data('editor');
			var text = '';
			switch (type) {
				case 'text':
					text = $('input', $this).val();
					break;
				case 'textarea':
					text = $('textarea', $this).val();
			}
			$this.html(text);
			$this.removeData('editing');
		}
		function toggle($this) {
			if ($this.data('editing')) {
				hide($this);
			} else {
				show($this);
			}
		}
		return this.each(function() {
			var $this = $(this);
			if (options == 'toggle') {
				toggle($this);
			} else if (options == 'show') {
				show($this);
			} else if (options == 'hide') {
				hide($this);
			}
		});
	}
})(jQuery);
