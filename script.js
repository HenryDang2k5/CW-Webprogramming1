// Mảng chứa các màu gradient đa sắc
const gradients = [
    'linear-gradient(45deg, #FF5733, #FF8D1A, #33FF57)', // Gradient từ cam đỏ sang cam, sau đó sang xanh lá
    'linear-gradient(45deg, #3357FF, #F1C40F, #8E44AD)', // Gradient từ xanh dương sang vàng, rồi tím
    'linear-gradient(45deg, #FF6347, #40E0D0, #FFD700)', // Gradient từ đỏ sang xanh ngọc, rồi vàng
    'linear-gradient(45deg, #1ABC9C, #9B59B6, #3498DB)', // Gradient từ xanh nước biển sang tím, rồi xanh dương
    'linear-gradient(45deg, #F39C12, #F1C40F, #D35400)', // Gradient từ vàng sang cam, rồi đỏ
    'linear-gradient(45deg, #00C9FF, #92FE9D, #FF9A8B)', // Gradient từ xanh biển sáng sang xanh lá, rồi hồng
    'linear-gradient(45deg, #8E44AD, #3498DB, #1ABC9C)', // Gradient từ tím sang xanh dương, rồi xanh nước biển
    'linear-gradient(45deg, #E74C3C, #F39C12, #8E44AD)', // Gradient từ đỏ sang vàng, rồi tím
    'linear-gradient(45deg, #FF00FF, #00FFFF, #FF7F50)', // Gradient từ hồng sang xanh dương, rồi cam
    'linear-gradient(45deg, #DFFF00, #FF1493, #9400D3)' // Gradient từ vàng sáng sang hồng, rồi tím đậm
];

let currentGradientIndex = 0; // Biến theo dõi gradient hiện tại

// Hàm thay đổi gradient nền
function changeBackgroundGradient() {
    const body = document.querySelector('body');
    body.style.background = gradients[currentGradientIndex]; // Thay đổi gradient nền
    currentGradientIndex = (currentGradientIndex + 1) % gradients.length; // Chuyển sang gradient tiếp theo, nếu đến cuối thì quay lại đầu
}

// Gọi hàm mỗi 5 giây (5000ms)
setInterval(changeBackgroundGradient, 5000); // 5000ms = 5 giây
