import javax.swing.*;
import java.awt.*;

public class Application {
    public static void main(String[] args) {
        SwingUtilities.invokeLater(Application::createAndShowGui);
    }

    private static void createAndShowGui() {
        JFrame frame = new JFrame("FOSSestate Web App");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(900, 600);
        frame.setLocationRelativeTo(null);

        JPanel root = new JPanel(new BorderLayout(16, 16));
        root.setBorder(BorderFactory.createEmptyBorder(20, 20, 20, 20));

        JLabel heading = new JLabel("FOSSestate Property Manager");
        heading.setFont(new Font(Font.SANS_SERIF, Font.BOLD, 28));
        heading.setHorizontalAlignment(SwingConstants.CENTER);
        root.add(heading, BorderLayout.NORTH);

        JTextArea details = new JTextArea(
                "Welcome to FOSSestate.\n\n" +
                "This lightweight Java desktop application is a starting point for property management workflows.\n" +
                "Add tenants, view leases, and track payments with a local or API-driven integration.\n\n" +
                "Use the server in ./php for backend data and the website in ./www for the public storefront."
        );
        details.setEditable(false);
        details.setFont(new Font(Font.MONOSPACED, Font.PLAIN, 14));
        details.setLineWrap(true);
        details.setWrapStyleWord(true);
        root.add(new JScrollPane(details), BorderLayout.CENTER);

        JButton launchButton = new JButton("Launch FOSSestate Dashboard");
        launchButton.addActionListener(e -> JOptionPane.showMessageDialog(frame,
                "Use the PHP API at http://localhost:8080/api/dashboard or open the website at http://localhost:3000.",
                "FOSSestate", JOptionPane.INFORMATION_MESSAGE));
        root.add(launchButton, BorderLayout.SOUTH);

        frame.setContentPane(root);
        frame.setVisible(true);
    }
}
