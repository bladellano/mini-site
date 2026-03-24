    <script>
        // Configurar chatbot
        window.CdnsChatConfig = {
            serverUrl: 'https://chat-server.cdnssystems.com.br',
            webhookId: 'c45fc06e-c47d-4d26-8362-c3513b4bea0b',
            position: 'bottom-right',
            primaryColor: '#5995f6',
            botName: 'Paul IA Tester',
            welcomeMessage: 'Oi! 👋 Sou o Paul, seu assistente virtual. Como posso ajudar?',
            placeholder: 'Pergunte-me qualquer coisa...',
            buttonIcon: '👨‍🦰'
        };

        // Adicionar scripts do chatbot
        const socketScript = document.createElement('script');
        socketScript.src = 'https://chat-server.cdnssystems.com.br/socket.io/socket.io.js';

        // Aguardar o Socket.IO carregar completamente antes de adicionar o widget
        socketScript.onload = () => {
            const widgetScript = document.createElement('script');
            widgetScript.src = 'https://chat-server.cdnssystems.com.br/widget.js';
            document.body.appendChild(widgetScript);
        };

        // Tratamento de erro caso o Socket.IO não carregue
        socketScript.onerror = () => {
            console.error('Erro ao carregar o Socket.IO. O chat não estará disponível.');
        };

        document.body.appendChild(socketScript);
    </script>
</body>
</html>
