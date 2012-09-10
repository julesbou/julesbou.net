all:
	make install
	make css
	make serve

install:
	git submodule update --init
	bundle install

css:
	lessc assets/less/style.less > assets/css/style.css

serve:
	jekyll serve --watch
