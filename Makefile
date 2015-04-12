css:
	lessc style.less | autoprefixer > style.css

watch:
		watchy -w style.less -- bash -c "make css"
