<footer class="footer text-white text-center py-5">
    <div class="container">
        <h5 class="fw-bold mb-3">TravelHub</h5>
        <p class="mb-2">&copy; <?php echo date('Y'); ?> TravelHub. All rights reserved.</p>
        <p class="mb-4">Contact us: <a href="mailto:info@travelhub.com" class="text-warning">info@travelhub.com</a> | Phone: +1-800-Travel</p>
        
        <div class="d-flex justify-content-center gap-3 mb-3">
            <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</footer>

<style>
.footer {
    background: linear-gradient(135deg, #003366, #0055a5);
}
.footer a {
    text-decoration: none;
}
.social {
    background: rgba(255,255,255,0.1);
    color: #fff;
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}
.social:hover {
    background: #f39c12;
    color: #fff;
    transform: translateY(-3px);
}
</style>
</body>
</html>
