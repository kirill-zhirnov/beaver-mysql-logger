<script type="text/javascript">
	var Singletone = (function() {
		var instance;

		return function Construct_singletone (name) {
			if (instance && typeof(instance[name]) != 'undefined') {
				return instance[name];
			}

			if (this && this.constructor === Construct_singletone) {
				if (typeof(instance) == 'undefined')
					instance = {};

				instance[name] = this;
			} else {
				return new Construct_singletone(name);
			}

			this.open();
		};

	} ());

	Singletone.prototype.open = function()
	{
		console.log(123);
	}

	var obj = new Singletone('test');
//	obj.open();

	var obj = new Singletone('test');
//	obj.open();

//	Singletone.open();
</script>