function Validator(){
    this.isEmpty = function(str){
        if(str == '') return true;
        return false;
    };

    this.isInt = function(str){
        var num = +str;
        if(typeof num === 'number' && !Number.isNaN(num) && Number.isInteger(num)){
            return true;
        }
        return false;
    };

    this.isFloat = function(str){
        var num = +str;
        if(typeof num === 'number' && !Number.isNaN(num)){
            return true;
        }
        return false;
    };

    this.isValidPrice = function(str){
        if(!this.isFloat(str)) return false;
        if(+str < 0) return false;
        return true;
    };

    this.isValidImage = function(file){
        var imagesTypes = [
            'image/jpeg',
            'image/gif',
            'image/png'
        ];

        return imagesTypes.includes(file.type);
    };
}