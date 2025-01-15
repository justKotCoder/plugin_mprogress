document.addEventListener('DOMContentLoaded', function() {
    const btn = document.createElement('button');
    btn.className = 'mprogress-floating-btn';
    btn.title = M.util.get_string('openmprogress', 'local_mprogress');
    document.body.appendChild(btn);

    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;

    btn.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);

    btn.addEventListener('click', function(e) {
        if (!isDragging) {
            window.location.href = M.cfg.wwwroot + '/local/mprogress/index.php';
        }
    });

    function dragStart(e) {
        initialX = e.clientX - btn.offsetLeft;
        initialY = e.clientY - btn.offsetTop;
        isDragging = true;
    }

    function drag(e) {
        if (isDragging) {
            e.preventDefault();
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
            btn.style.left = currentX + 'px';
            btn.style.top = currentY + 'px';
        }
    }

    function dragEnd() {
        setTimeout(() => {
            isDragging = false;
        }, 100);
    }
});