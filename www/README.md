# FOSSestate Website

This is the React-based website for FOSSestate, built with:
- **React** - UI framework
- **Tailwind CSS** - Styling
- **React Scripts** - Build tools

## Project Structure

```
www/
├── public/
│   └── index.html          # React root HTML
├── src/
│   ├── components/
│   │   ├── Header.jsx      # Navigation header
│   │   ├── Hero.jsx        # Hero section
│   │   ├── Features.jsx    # Features cards
│   │   ├── Dashboard.jsx   # Dashboard & stats
│   │   └── Footer.jsx      # Footer
│   ├── App.jsx             # Main App component
│   ├── index.js            # React entry point
│   └── index.css           # Tailwind CSS setup
├── package.json            # Dependencies
├── tailwind.config.js      # Tailwind configuration
└── postcss.config.js       # PostCSS configuration
```

## Installation

1. Navigate to the www directory:
   ```bash
   cd www
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

## Development

Start the development server:
```bash
npm start
```

The app will open at `http://localhost:3000`

## Build

Create a production build:
```bash
npm build
```

The build folder will contain the optimized files ready for deployment.

## Features

- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Modern UI with Tailwind CSS
- ✅ Component-based React architecture
- ✅ Dashboard with statistics
- ✅ Property management table
- ✅ Feature showcase
- ✅ Navigation and CTA buttons

## Next Steps

- Connect to PHP backend APIs in `/php/API/`
- Implement authentication pages (login.html, signup.html)
- Add property detail views
- Integrate with tenant management
- Connect financial reporting features
