import React, { useState } from 'react';

const Header = ({ token, onLoginClick, onLogout }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <header className="sticky top-0 z-100 bg-white shadow-md">
      <nav className="flex justify-between items-center max-w-6xl mx-auto px-8 py-4">
        <div className="flex items-center gap-2 text-2xl font-bold text-primary">
          🏠 FOSSestate
        </div>

        <ul className="hidden md:flex list-none gap-8">
          <li><a href="#home" className="text-gray-800 font-medium hover:text-primary transition">Home</a></li>
          <li><a href="#properties" className="text-gray-800 font-medium hover:text-primary transition">Properties</a></li>
          <li><a href="#features" className="text-gray-800 font-medium hover:text-primary transition">Features</a></li>
          <li><a href="#about" className="text-gray-800 font-medium hover:text-primary transition">About</a></li>
        </ul>

        <div className="flex items-center gap-4">
          {token ? (
            <button
              onClick={onLogout}
              className="hidden md:block px-6 py-2 border-2 border-red-400 text-red-500 font-semibold rounded hover:bg-red-50 transition"
            >
              Logout
            </button>
          ) : (
            <>
              <button
                onClick={onLoginClick}
                className="hidden md:block px-6 py-2 border-2 border-primary text-primary font-semibold rounded hover:bg-primary hover:text-white transition"
              >
                Login
              </button>
              <button
                onClick={onLoginClick}
                className="hidden md:block px-6 py-2 bg-primary text-white font-semibold rounded hover:bg-secondary transition"
              >
                Sign Up
              </button>
            </>
          )}
          <button
            className="md:hidden text-2xl"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
          >
            ☰
          </button>
        </div>
      </nav>

      {isMenuOpen && (
        <div className="md:hidden bg-white border-t border-gray-200 px-8 py-4">
          <ul className="flex flex-col gap-4">
            <li><a href="#home" className="text-gray-800 font-medium hover:text-primary">Home</a></li>
            <li><a href="#properties" className="text-gray-800 font-medium hover:text-primary">Properties</a></li>
            <li><a href="#features" className="text-gray-800 font-medium hover:text-primary">Features</a></li>
            <li><a href="#about" className="text-gray-800 font-medium hover:text-primary">About</a></li>
            {token ? (
              <button onClick={onLogout} className="text-left text-red-500 font-medium hover:text-red-700">Logout</button>
            ) : (
              <button onClick={onLoginClick} className="text-left text-gray-800 font-medium hover:text-primary">Login</button>
            )}
          </ul>
        </div>
      )}
    </header>
  );
};

export default Header;
