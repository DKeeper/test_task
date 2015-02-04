var helpers = {
    rules : {},

    init : function(config) {
        this.rules = config;
    },

    selectAvatar : function() {
        document.getElementById("registrationform-avatar").click();
        return false;
    },

    validateForm : function(id) {
        var form = document.getElementById(id);
        var errors = {count:0};
        for(ruleName in this.rules){
            var currentRule = this.rules[ruleName];
            var v = form.elements[id.replace("-","")+"-"+ruleName].value;
            for(var i=0;i<currentRule.length;i++){
                if(currentRule[i].type=="regExp"){
                    if(errors[ruleName]!=null){
                        var e = this.validateRegExp(v,currentRule[i]);
                        if(e!=null)
                            errors[ruleName] += e;
                    } else {
                        errors[ruleName] = this.validateRegExp(v,currentRule[i]);
                    }
                }
                if(currentRule[i].type=="length"){
                    if(errors[ruleName]!=null){
                        var e = this.validate(v,currentRule[i]);
                        if(e!=null)
                            errors[ruleName] += e;
                    } else {
                        errors[ruleName] = this.validate(v,currentRule[i]);
                    }
                }
                if(currentRule[i].type=="file"){
                    if(errors[ruleName]!=null){
                        var e = this.validateFile(form.elements[id.replace("-","")+"-"+ruleName].files[0],currentRule[i]);
                        if(e!=null)
                            errors[ruleName] += e;
                    } else {
                        errors[ruleName] = this.validateFile(form.elements[id.replace("-","")+"-"+ruleName].files[0],currentRule[i]);
                    }
                }
                if(errors[ruleName]!=null){
                    errors.count++;
                }
            }
        }
        for(attrName in errors){
            if(attrName=="count") continue;
            var el = form.elements[id.replace("-","")+"-"+attrName];
            var next = el.nextElementSibling;
            if(errors[attrName]!==null){
                el.classList.add("has-error");
                next.innerHTML = errors[attrName];
            } else {
                el.classList.remove("has-error");
                next.innerHTML = "";
            }
        }
        return !errors.count>0 ;
    },

    validate : function(param,config){
        var valid = true;
        switch(config[0]){
            case 'min':
                if(param.length<config[1]) valid=false;
                break;
            case 'max':
                if(param.length>config[1]) valid=false;
                break;
        }
        return valid ? null : config['message']+config[1];
    },

    validateRegExp : function(str,config){
        var valid = true;
        var regExp = new RegExp(config['pattern'].slice(1,config['pattern'].length-1));
        if(!regExp.test(str))
            valid = false;
        return valid ? null : config['message'];
    },

    validateFile : function(fileData,config){
        var valid = true;
        if(typeof config.allowedType !== "undefined" && typeof fileData !== "undefined"){
            fileType = fileData.type.replace("image/","");
            find = false;
            for(ft in config.allowedType){
                if(config.allowedType[ft]==fileType) {find = true; break;}
            }
            valid = find;
        }
        return valid ? null : config['message'];
    }
};