.PHONY: pages

all:
	make css
	make pages

serve:
	http-server ./ -p 8082

watch_css:
	watch "make css" styles --wait=1

css:
	lessc styles/_main.less | autoprefixer-cli > style.css

watch_pages:
	watch "make pages" pages --wait=1

pages:
	php build.php
