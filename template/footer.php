</main>
        
        <!-- Modern Footer -->
        <footer class="footer-modern">
            <div class="container">
            <div class="row">
                <!-- Brand Section -->
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <h5>BelajarDigital</h5>
                            <p class="footer-description">
                                Platform pembelajaran online modern yang dirancang untuk mahasiswa dan pengajar. 
                                Belajar kapan saja, di mana saja dengan teknologi terkini.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="footer-social">
                        <a href="https://www.instagram.com/doremifa.jri/" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/andhika-fajri-septiawan" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 mb-4">
                    <h6 class="footer-title">Navigasi</h6>
                    <ul class="footer-links">
                        <li>
                            <a href="/belajardigital/index.php" class="footer-link">
                                <i class="fas fa-home"></i>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="/belajardigital/halaman/mahasiswa/courses.php" class="footer-link">
                                <i class="fas fa-book-open"></i>
                                Kursus
                            </a>
                        </li>
                        <li>
                            <a href="/belajardigital/halaman/mahasiswa/profile.php" class="footer-link">
                                <i class="fas fa-user"></i>
                                Profil
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-question-circle"></i>
                                Bantuan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 mb-4">
                    <h6 class="footer-title">Hubungi Kami</h6>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:dhikafajri@gmail.com">dhikafajri@gmail.com</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Telepon</strong>
                                <a href="tel:+6281376449306">+62 813-7644-9306</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Lokasi</strong>
                                <span>Jakarta, Indonesia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Divider -->
                <div class="footer-divider"></div>

                <!-- Bottom Section -->
                <div class="footer-bottom">
                    <p class="copyright" style="text-align: center; font-size: 0.9rem; color: var(--footer-text-muted); margin: 0;">&copy; <?= date('Y') ?> BelajarDigital. Developed by Andhika Fajri Septiawan.</p>
                </div>
            </div>
        </footer>

        <!-- Back to Top Button -->
        <button class="back-to-top" id="backToTop" aria-label="Back to top">
            <i class="fas fa-chevron-up"></i>
        </button>

        <!-- Modern Footer Styles -->
        <style>
            :root {
                --footer-bg: linear-gradient(135deg, #1f2937 0%, #111827 100%);
                --footer-text: #d1d5db;
                --footer-text-muted: #9ca3af;
                --footer-accent: #6366f1;
                --footer-border: #374151;
            }

            .footer-modern {
                background: var(--footer-bg);
                color: var(--footer-text);
                padding: 4rem 0 2rem;
                margin-top: auto;
            }

            .footer-brand {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .footer-brand i {
                font-size: 2rem;
                color: var(--footer-accent);
                margin-top: 0.25rem;
            }

            .footer-brand h5 {
                color: white;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .footer-description {
                color: var(--footer-text-muted);
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .footer-title {
                color: white;
                font-weight: 600;
                margin-bottom: 1.5rem;
                font-size: 1.1rem;
            }

            .footer-links {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .footer-links li {
                margin-bottom: 0.75rem;
            }

            .footer-link {
                color: var(--footer-text-muted);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }

            .footer-link:hover {
                color: var(--footer-accent);
                transform: translateX(5px);
            }

            .footer-link i {
                width: 16px;
                font-size: 0.8rem;
            }

            .footer-social {
                display: flex;
                gap: 1rem;
            }

            .social-link {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.1);
                color: var(--footer-text);
                border-radius: 50%;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 1px solid var(--footer-border);
            }

            .social-link:hover {
                background: var(--footer-accent);
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
            }

            .footer-contact {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .contact-item {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                color: var(--footer-text-muted);
            }

            .contact-item i {
                color: var(--footer-accent);
                margin-top: 0.25rem;
            }

            .contact-item strong {
                color: var(--footer-text);
                display: block;
                font-size: 0.9rem;
            }

            .contact-item a {
                color: var(--footer-text-muted);
                text-decoration: none;
                font-size: 0.9rem;
            }

            .contact-item a:hover {
                color: var(--footer-accent);
            }

            .footer-divider {
                height: 1px;
                background: var(--footer-border);
                margin: 3rem 0 2rem;
            }

            .footer-bottom {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .copyright {
                color: var(--footer-text-muted);
                margin: 0;
                font-size: 0.9rem;
            }

            .footer-bottom-links {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .footer-link-small {
                color: var(--footer-text-muted);
                text-decoration: none;
                font-size: 0.85rem;
                transition: color 0.3s ease;
            }

            .footer-link-small:hover {
                color: var(--footer-accent);
            }

            .divider {
                color: var(--footer-border);
            }

            .back-to-top {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                width: 50px;
                height: 50px;
                background: var(--footer-accent);
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
                z-index: 1000;
            }

            .back-to-top.show {
                opacity: 1;
                visibility: visible;
            }

            .back-to-top:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .footer-modern {
                    padding: 3rem 0 1.5rem;
                }

                .footer-brand {
                    flex-direction: column;
                    text-align: center;
                }

                .footer-social {
                    justify-content: center;
                }

                .footer-bottom {
                    flex-direction: column;
                    text-align: center;
                }
            }

            /* Ensure footer stays at bottom */
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            main {
                flex: 1;
            }
        </style>

        <!-- Back to Top Script -->
        <script>
            // Back to top functionality
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            });
            
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Dynamic year update
            document.querySelector('.copyright').innerHTML = 
                `&copy; ${new Date().getFullYear()} BelajarDigital. Developed by Andhika Fajri Septiawan.`;
        </script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/aset/js/script.js"></script>
    </body>
</html>
