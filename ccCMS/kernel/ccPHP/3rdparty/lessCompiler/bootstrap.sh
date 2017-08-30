#!/bin/sh

compileOptions=""

inputBootStrap="../bootstrap.less"
outputBootStrap="../../styles_min/00_bootstrap.css"

inputBootStrapResponsive="../responsive.less"
outputBootStrapResponsive="../../styles_min/01_bootstrap_responsive.css"

inputSchwaebische="../schwaebische.less"
outputSchwaebische="../../styles_min/02_schwaebische.css"

rm "$outputBootStrap"
rm "$outputBootStrapResponsive"
rm "$outputSchwaebische"

./plessc "$inputBootStrap" "$outputBootStrap"
./plessc "$inputBootStrapResponsive" "$outputBootStrapResponsive"
./plessc "$inputSchwaebische" "$outputSchwaebische"

