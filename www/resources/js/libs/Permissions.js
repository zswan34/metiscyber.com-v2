Permissions = {
    hasAny: (list, required) => {
        if (Array.isArray(required)) {
            for (let i = 0; i < required.length; i++) {
                for (let j = 0; j < list.length; j++) {
                   if (list[j].name === required[i]) {
                       return true;
                   }
                }
            }
            return false;
        } else {
            for (let i = 0; i < list.length; i++) {
                if (list[i].name === required) {
                    return true;
                }
            }
            return false;
        }
    }
};
