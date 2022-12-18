// $('.message a').click(function() {
//     $('form').animate({ height: "toggle", opacity: "toggle" }, "slow");
// });

const signupBtn = document.getElementById("signupBtn");
signupBtn.addEventListener("click", (e) => {
    const nameSignUp = document.getElementById("nameSignUp");
    const emailSignUp = document.getElementById("emailSignUp");
    const passwordSignUp = document.getElementById("passwordSignUp");
    const passwordRetype = document.getElementById("passwordRetype");
    // if (nameSignUp.value == "") {
    //     document.getElementById("errMessage1").innerHTML = ("Vui lòng nhập tên của bạn </br></br>");
    //     e.preventDefault();
    // } else if (emailSignUp.value == "") {
    //     document.getElementById("errMessage1").innerHTML = ("Vui lòng nhập email của bạn </br></br>");
    //     e.preventDefault();
    // } else if (passwordSignUp.value == "") {
    //     document.getElementById("errMessage1").innerHTML = ("Vui lòng nhập mật khẩu</br></br>");
    //     e.preventDefault();
    // } else if (passwordSignUp.value.length < 6) {
    //     document.getElementById("errMessage1").innerHTML = ("Mật khẩu của bạn phải có ít nhất 6 kí tự</br></br>");
    //     e.preventDefault();
    // } else if (passwordRetype.value == "") {
    //     document.getElementById("errMessage1").innerHTML = ("Vui lòng nhập xác nhận lại mật khẩu</br></br>");
    //     e.preventDefault();
    // } else if (passwordRetype.value != passwordSignUp.value) {
    //     document.getElementById("errMessage1").innerHTML = ("Mật khẩu và xác nhận mật khẩu không trùng khớp</br></br>");
    //     e.preventDefault();
    // } else 
    if (!validateEmail(emailSignUp.value)) {
        document.getElementById("errMessage1").innerHTML = ("Email không hợp lệ </br></br>");
        e.preventDefault();
    }
})

// const loginBtn = document.getElementById("loginBtn");
// loginBtn.addEventListener("click", (e) => {

//     const email = document.getElementById("email");
//     const password = document.getElementById("password");
//     let er_code = document.getElementById("err_code_1").value;
//     console.log(er_code);

//     if (er_code === "0") {
//         document.getElementById("errMessage2").innerHTML = ("Vui lòng nhập email và mật khẩu của bạn </br></br>");
//         e.preventDefault();
//     } else if (er_code === "1") {
//         document.getElementById("errMessage2").innerHTML = ("Vui lòng nhập email của bạn </br></br>");
//         e.preventDefault();
//     } else if (er_code === "3") {
//         document.getElementById("errMessage2").innerHTML = ("Mật khẩu của bạn không đúng. Vui lòng nhập lại</br></br>");
//         e.preventDefault();
//     } else if (er_code === "4") {
//         document.getElementById("errMessage2").innerHTML = ("Tài khoản không tồn tại. Vui lòng kiểm tra lại</br></br>");
//         e.preventDefault();
//     } else {
//         {
//             if (er_code === "2") {
//                 document.getElementById("errMessage2").innerHTML = ("Vui lòng nhập mật khẩu của bạn</br></br>");
//                 e.preventDefault();
//             } else if (password.value.length < 6) {
//                 document.getElementById("errMessage2").innerHTML = ("Mật khẩu của bạn phải có ít nhất 6 kí tự</br></br>");
//                 e.preventDefault();
//             }
//         }
//     }



// })

const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

// const nameSignupClick = document.getElementById("nameSignUp");
// nameSignupClick.addEventListener("click", (e) => {
//     document.getElementById("errMessage1").innerHTML = ("");
//     e.preventDefault();
// })

// const emailSignUpClick = document.getElementById("emailSignUp");
// emailSignUpClick.addEventListener("click", (e) => {
//     document.getElementById("errMessage1").innerHTML = ("");
//     e.preventDefault();
// })

// const passwordSignUpClick = document.getElementById("passwordSignUp");
// passwordSignUpClick.addEventListener("click", (e) => {
//     document.getElementById("errMessage1").innerHTML = ("");
//     e.preventDefault();
// })

// const passwordRetypeClick = document.getElementById("passwordRetype");
// passwordRetypeClick.addEventListener("click", (e) => {
//     document.getElementById("errMessage1").innerHTML = ("");
//     e.preventDefault();
// })

const emailClick = document.getElementById("email");
emailClick.addEventListener("click", (e) => {
    document.getElementById("errMessage2").innerHTML = ("");
    e.preventDefault();
})

const passwordClick = document.getElementById("password");
passwordClick.addEventListener("click", (e) => {
    document.getElementById("errMessage2").innerHTML = ("");
    e.preventDefault();
})

const resetPasswordClick = document.getElementById("ResetButton");