First enter in the translator/system_settings folder:
	cd /var/www/html/phpframework/trunk/other/availablemodule/translator/system_settings
	
Then before you zip this module, please execute this line to overwrite the file ../presentation/translations/pt.php:
	php serialization_files/serialize_modules_langs.php > ../presentation/translations/pt.php

Then after you zipped and installed the translation module, please execute the following lines:
	NOTE THAT IF YOU EXECUTE THESE LINES, YOU WILL OVERWRITE THE PROJECT FILES:
		layer/presentation/common/src/module/translator/translations/projects/condo/pt.php
		layer/presentation/common/src/module/translator/translations/projects/mastercondo/pt.php
		layer/presentation/common/src/module/translator/translations/projects/hospital/pt.php
	(YOU SHOULD ONLY DO THIS FOR THE FIRST TIME)
	
	IF YOU WISH TO PROCEED EXECUTE:
		php serialization_files/serialize_mastercondo_langs.php > ~/Desktop/pt.php
		sudo cp ~/Desktop/pt.php ../../../../app/layer/presentation/common/src/module/translator/translations/projects/mastercondo/pt.php
		
		php serialization_files/serialize_condo_langs.php > ~/Desktop/pt.php
		sudo cp ~/Desktop/pt.php ../../../../app/layer/presentation/common/src/module/translator/translations/projects/condo/pt.php
		
		php serialization_files/serialize_hospital_langs.php > ~/Desktop/pt.php
		sudo cp ~/Desktop/pt.php ../../../../app/layer/presentation/common/src/module/translator/translations/projects/hospital/pt.php

