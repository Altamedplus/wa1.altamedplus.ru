import '../css/module/showflake.scss'
        function createSnowflakes(count:number) {
            const container = document.querySelector('header');
            
            for (let i = 0; i < count; i++) {
                const snowflake = document.createElement('div');
                snowflake.className = 'snowflake';
                
                // Случайные параметры для каждой снежинки
                const size = Math.random() * 6 + 3; // от 3px до 9px
                const left = Math.random() * 100; // от 0% до 100%
                const animationDuration = Math.random() * 10 + 10; // от 10s до 20s
                const animationDelay = Math.random() * 10; // до 10s задержки
                const opacity = String(Math.random() * 0.5 + 0.4); // от 0.4 до 0.9
                
                // Применяем стили
                snowflake.style.width = `${size}px`;
                snowflake.style.height = `${size}px`;
                snowflake.style.left = `${left}%`;
                snowflake.style.animationDuration = `${animationDuration}s`;
                snowflake.style.animationDelay = `${animationDelay}s`;
                snowflake.style.opacity = opacity;
                
                // Добавляем небольшое боковое смещение в анимации
                const translateX = (Math.random() - 0.5) * 60; // от -30px до 30px
                snowflake.style.setProperty('--translateX', `${translateX}px`);
                
                // Переопределяем анимацию для каждой снежинки
                snowflake.style.animation = `fall ${animationDuration}s linear ${animationDelay}s infinite`;
                
                container.appendChild(snowflake);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            createSnowflakes(150);
        });