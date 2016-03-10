css:
	lessc style.less | autoprefixer-cli > style.css

watch:
		watchy -w style.less -- bash -c "make css"
