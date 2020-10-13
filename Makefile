all:
	make css

install:
	npm install --global autoprefixer-cli
	npm install --global less

serve:
	http-server ./ -p 8082

watch_css:
	make css
	watch "make css" styles --wait=1

css:
	lessc styles/_main.less | autoprefixer-cli > style.css