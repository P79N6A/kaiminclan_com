<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => '2016080300155900',

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEApBGjrFylu8tKYo6LGannS99wwyuVcLvBxITgenx/XTS3wzi9lNRd3YDrxQie0GUsPTpSLO2huiOq7aHgV0722ovkV0Jf2h/gQNmo59cTe3R+eR4kgit680D/jlSkKYAsKw34gq1ee6RdXNIhJSEhGrMH/IKOWgiWqJMWxtAWlleZ2w1uZHKc+yg6jvuio6XBxqa4294bGZ8ZnKXYoSqOx21P7Kh0Pm1/aguPkdnrWgtczaM/ephT3PTEZ9rSEHSRo2hCt4QvOS0bAgJa4x8WgTIjbV0igMX8UrntjnyaDzG2+rgRCY+d5qRxMu2kbCHffT4jFBiWpg/qqbecF0GJDQIDAQABAoIBAGxv6CB8X2GeO/ylv5PnsqJ3/HxSKwQGZEvxkrgB3uAIfsf9kXdzYNKMacehKe4MYE/bYwKk0HZJKqjCi5bI7uiE+xcdHGL7HGlgMn2ArjSzSv/cBz8tY6awt/cnRBTVzNtr8WrVERRhDv/RNeznV4zg9Q9Ubqdc885Y4P3n/HXCQz//Ga65xy+m2vHzO72i8PauFKgWFvrHdAgSQFnSDStsudK3RH4dWMEV+uLSZegdoGviSdeDpyJoMWIeSyXChuPX5KsIwsju2a44a96WqOgH8w/EMgiZzXZe0EmaroVmzy/0yazylbFl+F/zR7pbIsyp2qGLZA0aEexGGKWrbtUCgYEA0rLpgI3QR6V2i2bNUEt7BpScbAMVfcXazIEf4vUR6d630dZmrbAZqEgZXks+wrJXDLd438+xpnv1VPDttn5MrqdgleTPLp4ZxOTSC5jgLkdX2JQA4IeGTmctqBj3W0cxENTI+YFUtLHUsSyvDI2xlsud0W4zVl5Z5Eqs6t1bdKMCgYEAx1gqHO6OTF3O+8ETmn8ulkhbW0LCxNx8F3IeJQY36kGj5v8EfFVgubD99bhPhA8hK2ShhlnYhL10dV3znoFIvNH36l3IfUVx5VXWWaOCmXx/NDVzlJx0KtxNzXfFv5NgUFbn7M8V/He8EIiT/1c+ZqjjGjVDzICgHx8BNPc7No8CgYEAsUx0SQUMuv11X6DGLEcX41WyuwFtWETh9SzDFEx9InuT2zO7e3SwYncpQbu3uEYyEjVhtZQkPaAZtbx3jBWDmYSnNfswjF3l+VVDmxatrsPCHDGO+2GPlvIj7Uv0+g5SL4OfCrXV/aeW/vBG7R1ezzHQP6en84m5wu1DzO8wQlkCgYAB7nXCAAG69bG6uL0y4waViC/ya2wtsiE1rPJj03KZP3eSFmh5t+4O/osHZXjHw8nDfN8fySES8C7/sCBbKc+FnNc1GyGFLTYUTcec6LusNwuYeugayYVKoQXT9tQgrhHh1WW74qnI33QgCOK+N58wKXTB3UBB7AEhLBC77aVSOwKBgQCefoLRcs6Q8iiSRGDRPl0kxxzzjosfFFK3d8EsxT6fCJ9MnomMxYA8Z7yZue5uj27JlXo+5T4jq7g8uKMhCR0Jp5UBEQlAZpkiaodC/BYwOE34/KUoKJ2jTEMya6ZvUvB/297AbXgFUk/6+29oZ0Ei6yyJNzKeokKJvDCmZkvcEA==",
		
		//异步通知地址  必须公网可正常访问
		'notify_url' => fetch_site_base_url('http://wine.laozongyi.com/paynotify/alipay.html'),
		
		//同步跳转
		// 'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",
		'return_url' => fetch_site_base_url('http://wine.laozongyi.com/paynotify/alipay.html'),

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关	 
		// 'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
		
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmRs/L4mhbEZQflGR50gHfUcnwduzPgGYC/Zxn4jfE4SfUqX2RpsR2+ltyuUpQvFKY8u/aEdJIrOu/txjjgqAVLT8zg+PKcf7bwWOTh7uboJyatoV82ALsiQOF+oNWgYNq9Np4Q5TtDWaKkKYD4XRhvPvEBt0m/WV0aT+WC8xFisyaeuD5qCmziAPNkEa1fyG4eUjEOjwq6t1PsHn5MjolRJSX164oH5FXhgw+EOPDuMytMqY3YXH4qMgGpXpqwjdZweyJwY9ZDQQyGnHv6Lo7CPE0Ya7XdPNZBa7cI4795sj6AlmeC/XXVT2xCD4z8+67zOi9B5fBdiHhHc++jog4wIDAQAB",
);