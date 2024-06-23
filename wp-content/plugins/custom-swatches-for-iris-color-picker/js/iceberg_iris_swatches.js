 jQuery(document).ready(function($){
 			try {
            $.wp.wpColorPicker.prototype.options = {
                palettes: [
                iceberg_iris_color.iceberg_iris_color1,
                iceberg_iris_color.iceberg_iris_color2,
                iceberg_iris_color.iceberg_iris_color3,
                iceberg_iris_color.iceberg_iris_color4,
                iceberg_iris_color.iceberg_iris_color5,
                iceberg_iris_color.iceberg_iris_color6,
                iceberg_iris_color.iceberg_iris_color7,
                iceberg_iris_color.iceberg_iris_color8
                ],
                hide: true
            };
           }
           catch(e) {}
        });