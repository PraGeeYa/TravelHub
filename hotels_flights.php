<?php 
session_start(); 
include 'db.php'; 
?>
<?php include 'includes/header.php'; ?>

<style>
    body {
        padding-top: 90px;
        background: #f5f7fa;
    }
    .search-section {
        background: #fff;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        margin-top: 30px;
    }
    .search-title {
        text-align: center;
        margin-bottom: 30px;
    }
    .search-title h1 {
        font-weight: 700;
        color: #2c3e50;
    }
    .search-title p {
        color: #7f8c8d;
    }
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px;
    }
    .btn-search {
        width: 100%;
        background: linear-gradient(135deg, #36d1dc, #5b86e5);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-search:hover {
        background: linear-gradient(135deg, #5b86e5, #36d1dc);
        transform: translateY(-2px);
    }
    .icon-input {
        position: relative;
    }
    .icon-input i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #5b86e5;
        font-size: 18px;
    }
    .icon-input input {
        padding-left: 45px;
    }
</style>

<div class="container">
    <div class="search-section">
        <div class="search-title">
            <h1><i class="fas fa-plane-departure"></i> Hotels & Flights</h1>
            <p>Find the best options for your next trip 🌍</p>
        </div>
        <form>
            <div class="mb-3 icon-input">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" class="form-control" placeholder="Destination">
            </div>
            <div class="mb-3 icon-input">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" class="form-control" placeholder="Check-in">
            </div>
            <div class="mb-3 icon-input">
                <i class="fas fa-calendar-check"></i>
                <input type="date" class="form-control" placeholder="Check-out">
            </div>
            <button type="submit" class="btn btn-search"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
