
#
# CSS Compile
#

#
# Closure Templates
#
java -jar /SVN/closure/closure-templates/SoyToJsSrcCompiler.jar \
    --outputPathFormat ./application/themes/shattered/js/buildTemplate.soy.js  \
    --shouldGenerateJsdoc \
    --shouldProvideRequireSoyNamespaces \
    ./application/themes/shattered/js/buildTemplate.soy
    
#
# Javascript Komprimierung
#
python /SVN/closure/closure-library/closure/bin/calcdeps.py \
    --path /SVN/closure/closure-library \
    --compiler_jar /SVN/closure/closure-compiler/compiler.jar  \
    --output_mode compiled  \
    --compiler_flags="--formatting=PRETTY_PRINT"  \
    --compiler_flags="--source_map_format=V1"  \
    --compiler_flags="--create_source_map=./application/js/compile-simple-map"  \
    --compiler_flags="--compilation_level=SIMPLE_OPTIMIZATIONS"  \
    --input ./application/js/function_debug.js \
    --input ./application/js/function_console.js  \
    --input ./application/js/prototypes.js  \
    > ./application/themes/shattered/js/compile-simple.js

exit 0