// liquidテンプレートエンジンとangular.jsは記述の仕方がぶつかるのでangular.jsのstartSymbolを変更する
angular.module('michis-note', ['ngResource']).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);
