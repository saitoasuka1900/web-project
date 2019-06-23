function login_check() {
    let element = document.getElementById("login-form");
    if(element.username.value==""){
        window.alert("用户名不能为空");
    }else if(element.password.value==""){
        window.alert("密码不能为空");
    }else {
        let login_form = document.createElement("form");
        login_form.action = "forum-login.php";
        //若是打开新窗口，form的target属性要设置为'_blank'
        login_form.method = "post";
        login_form.style.display = "none";
        //添加参数
        let paraments = new Array();
        paraments.push({ name: "username", value: element.username.value });
        paraments.push({ name: "password", value: element.password.value });
        paraments.push({ name: "login", value: "Login" });
        for (let item in paraments) {
            let opt = document.createElement("textarea");
            opt.name = paraments[item].name;
            opt.value = paraments[item].value;
            login_form.appendChild(opt);
        }
        document.body.appendChild(login_form);
        //提交数据
        login_form.submit();
        element.reset();
    }
}

function register_check() {
    let element = document.getElementById("register-form");
    if(element.username.value==""){
        window.alert("用户名不能为空");
    }else if(element.password.value==""){
        window.alert("密码不能为空");
    }else if(element.password.value.length<6||element.password.value.length>20){
        window.alert("密码长度必须为6到20位");
    }else if(element.age.value==""){
        window.alert("年龄不能为空");
    }else if(element.job.value==""){
        window.alert("职业不能为空");
    }else if(element.sex.value==""){
        window.alert("性别不能为空");
    } else {
        let register_form = document.createElement("form");
        register_form.action = "forum-register.php";
        //若是打开新窗口，form的target属性要设置为'_blank'
        register_form.method = "post";
        register_form.style.display = "none";
        //添加参数
        let paraments = new Array();
        paraments.push({ name: "username", value: element.username.value });
        paraments.push({ name: "password", value: element.password.value });
        paraments.push({ name: "age", value: element.age.value });
        paraments.push({ name: "job", value: element.job.value });
        paraments.push({ name: "sex", value: element.sex.value });
        paraments.push({ name: "register", value: "register" });
        for (let item in paraments) {
            let opt = document.createElement("textarea");
            opt.name = paraments[item].name;
            opt.value = paraments[item].value;
            register_form.appendChild(opt);
        }
        document.body.appendChild(register_form);
        //提交数据
        register_form.submit();
        element.reset();
    }
}