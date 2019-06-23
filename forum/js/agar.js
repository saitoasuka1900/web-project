typenum = [0, 0, 0, 0]
radius = [[3, 1], [5, 10], [5, 25]]
function randomHexColor() {	//随机生成十六进制颜色
    return '#' + ('00000' + (Math.random() * 0x1000000 << 0).toString(16)).substr(-6);
}
function randomtype(){
    if(typenum[0] < 20){
        return 0;
    }
    else{
        return (typenum[1] < 10) ? 1 : 2;
    }
}
class Circle {
    //创建对象
    //以一个圆为对象
    //设置随机的 x，y坐标，r半径，vx，vy移动的速度
    //this.r是创建圆的半径，参数越大半径越大
    //this.vx,this.vy是移动的速度，参数越大移动越快
    //this.dx,this.dy是速度减小的快慢，参数越大速度减小越快
    //0为食物，1为角色，2为刺球
    constructor(x, y, type) {
        this.type = type;
        typenum[type] += 1;
        this.x = x;
        this.y = y;
        this.r = Math.random() * radius[type][0] + radius[type][1];
        if(this.type == 2||this.type == 0){
            this.vx = this.vy = 0;
        }
        else{
            this.vx = Math.random() * 0.5;
            if (Math.round(Math.random()) == 0)
                this.vx = this.vx * -1;
            this.vy = Math.sqrt(0.25 - this.vx * this.vx);
            if (Math.round(Math.random()) == 0)
                this.vy = this.vy * -1;
        }
        this.isDead = false;
        if(this.type == 2||this.type == 0)
            this.age = 200;
        else
            this.age = 0;
        this.color = randomHexColor();
        this.center_color = randomHexColor();
    }

    //canvas 画圆
    //画圆就是正常的用canvas画一个圆
    drawCircle(ctx) {
        if(this.type == 2){
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.closePath();
            ctx.fillStyle = this.center_color;
            ctx.fill();

            ctx.beginPath();
            ctx.strokeStyle = this.color;
            ctx.lineWidth = 2;
            ctx.moveTo(this.x + this.r * Math.cos(0), this.y + this.r * Math.sin(0));
            for (let a = 0; a < 360; a++) {
                ctx.lineTo(this.x + (this.r + 2 * Math.sin(a * 2)) * Math.cos(Math.PI / 180.0 * a),
                    this.y + (this.r + 2 * Math.sin(a * 2)) * Math.sin(Math.PI / 180.0 * a))
            }
            ctx.stroke();
            return;
        }
        if(this.age < 200) {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r + 3, 0, 360)
            ctx.closePath();
            ctx.fillStyle = "#FF6600";
            ctx.fill();
        }
        ctx.beginPath();
        //arc() 方法使用一个中心点和半径，为一个画布的当前子路径添加一条弧。
        ctx.arc(this.x, this.y, this.r, 0, 360)
        ctx.closePath();
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    // 圆圈移动
    // 圆圈移动的距离必须在屏幕范围内
    move(w, h) {
        this.age += 1;
        this.x = (this.x + this.vx + w) % w;
        this.y = (this.y + this.vy + h) % h;
        if(this.r >= 40)
            this.r -= 0.01;
    }
}
//更新页面用requestAnimationFrame替代setTimeout
window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
let canvas = document.getElementById('canvas');
let ctx = canvas.getContext('2d');
let w = canvas.width = canvas.offsetWidth;
let h = canvas.height = canvas.offsetHeight;
circles = [];
ctx.globalAlpha = 0.6;
for (let i = 0; i < 35; i++) {
    circles.push(new Circle(Math.random() * w, Math.random() * h, randomtype()));
}
let dist = function (x1, y1, x2, y2){
    return Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2))
}
let draw_background = function(ctx ,w ,h){
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#eee";
    ctx.beginPath();
    for (var i = 0; i <= w; i += 20) {
        ctx.moveTo(i, 0);
        ctx.lineTo(i, h);
    }
    for (var i = 0; i <= h; i += 20) {
        ctx.moveTo(0, i);
        ctx.lineTo(w, i);
    }
    ctx.stroke();
}
let draw = function () {
    ctx.clearRect(0, 0, w, h);
    draw_background(ctx, w, h);
    while(typenum[2] < 5 || typenum[1] < 10 || typenum[0] < 20) {
        circles.push(new Circle(Math.random() * w, Math.random() * h, randomtype()));
    }
    for (let i = 0; i < circles.length; i++) {
        if(circles[i].isDead || circles[i].type != 1)continue;
        for(let j = 0; j < circles.length; j++){
            if(circles[j].isDead || i == j || circles[j].age < 200)continue;
            if(circles[i].r - circles[j].r <= 0.5)continue;
            let d = dist(circles[i].x, circles[i].y, circles[j].x, circles[j].y);
            if(d <= circles[i].r && circles[j].type != 3){
                circles[j].isDead = true;
                typenum[circles[j].type] -= 1;
                circles[i].r += (circles[j].r / 2);
                if(circles[j].type == 2) {
                    circles[i].isDead = true;
                    typenum[circles[i].type] -= 1;
                    for(let k = 1; k <= circles[i].r / radius[1][1]; ++k) {
                        circles.push(new Circle(circles[i].x, circles[i].y, 1));
                    }
                }
            }
        }
    }
    for (var i = 0; i < circles.length; i++) {
        if (circles[i].isDead == true) {
            circles.splice(i, 1);
        }
    }
    for (let i = 0; i < circles.length; i++) {
        circles[i].move(w, h);
    }
    for (let i = 0; i < circles.length; i++) {
        circles[i].drawCircle(ctx);
    }
    requestAnimationFrame(draw)
}
window.addEventListener('load', draw());