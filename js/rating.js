document.addEventListener("DOMContentLoaded", () => {
    const starsContainer = document.getElementById("stars-container");
    if (starsContainer) {
        const stars = starsContainer.querySelectorAll("i");
        const averageRating = parseFloat(starsContainer.dataset.averageRating);
        const bookId = starsContainer.dataset.bookId;
        const userRating = parseInt(starsContainer.dataset.userRating, 10);
        let currentRating = userRating || 0; // Avaliação atual do usuário, ou 0
        let isHovering = false;
        // Exibe as estrelas com base na média inicialmente
        updateStarsDisplay(averageRating);
        // Destacar estrelas ao passar o mouse
        stars.forEach((star, index) => {
            star.addEventListener("mouseover", () => {
                isHovering = true;
                highlightStars(index + 1);
            });
            star.addEventListener("mouseout", () => {
                isHovering = false;
                updateStarsDisplay(currentRating || averageRating); // Voltar para a média ou avaliação do usuário
            });
            star.addEventListener("click", () => {
                currentRating = index + 1;
                submitRating(bookId, currentRating);
            });
        });
        function highlightStars(count) {
            stars.forEach((star, index) => {
                if (index < count) {
                    star.classList.remove("far");
                    star.classList.add("fas");
                } else {
                    star.classList.remove("fas");
                    star.classList.add("far");
                }
            });
        }
        function updateStarsDisplay(rating) {
            stars.forEach((star, index) => {
                const isHalfStar = rating - index >= 0.5 && rating - index < 1;
                if (index < Math.floor(rating)) {
                    star.classList.remove("far", "fa-star-half-alt");
                    star.classList.add("fas");
                } else if (isHalfStar) {
                    star.classList.remove("far", "fas");
                    star.classList.add("fas", "fa-star-half-alt");
                } else {
                    star.classList.remove("fas", "fa-star-half-alt");
                    star.classList.add("far");
                }
            });
        }
        function submitRating(bookId, rating) {
            fetch("submit_rating.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ book_id: bookId, rating: rating }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Thank you for your rating!");
                        currentRating = rating;
                        if (!isHovering) {
                            updateStarsDisplay(currentRating);
                        }
                        window.location.reload();
                    } else {
                        alert("Failed to submit rating: " + data.error);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while submitting your rating.");
                });
        }
    }
});