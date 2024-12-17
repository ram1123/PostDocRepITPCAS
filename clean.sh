#!/bin/bash

find . -name "*.aux" -delete
find . -name "*.log" -delete
find . -name "*.synctex.gz" -delete
find . -name "*.fdb_latexmk" -delete
find . -name "*.fls" -delete
find . -name "*.blg" -delete
find . -name "*.bbl" -delete
find . -name "*.out" -delete
find . -name "*-blx.bib" -delete
find . -name "*.run.xml" -delete
find . -name "*.bcf*" -delete
