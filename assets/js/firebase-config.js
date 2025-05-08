// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import {
  getAuth,
  signInWithPhoneNumber,
  PhoneAuthProvider,
  signInWithCredential,
} from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-analytics.js";

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyBfM7gGwpnypQVQ-XDvtFs1whc-Tp99PU4",
  authDomain: "fayyaz-travels-otp.firebaseapp.com",
  projectId: "fayyaz-travels-otp",
  storageBucket: "fayyaz-travels-otp.firebasestorage.app",
  messagingSenderId: "674714641427",
  appId: "1:674714641427:web:bf9ad4082798dc042a4be6",
  measurementId: "G-N7MQE92VMV",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const analytics = getAnalytics(app);

// OTP verification variables
let verificationId = null;
let otpTimer = null;
let timeLeft = 60;

// Function to start OTP timer
function startOtpTimer() {
  timeLeft = 60;
  document.getElementById("resendOtp").disabled = true;
  document.getElementById("otpTimer").textContent = `Resend in ${timeLeft}s`;

  otpTimer = setInterval(() => {
    timeLeft--;
    document.getElementById("otpTimer").textContent = `Resend in ${timeLeft}s`;

    if (timeLeft <= 0) {
      clearInterval(otpTimer);
      document.getElementById("resendOtp").disabled = false;
      document.getElementById("otpTimer").textContent = "";
    }
  }, 1000);
}

// Function to send OTP
async function sendOtp(phoneNumber) {
  try {
    const appVerifier = new firebase.auth.RecaptchaVerifier("submit-btn", {
      size: "invisible",
      callback: function (response) {
        // reCAPTCHA solved, allow signInWithPhoneNumber.
      },
    });

    const confirmation = await signInWithPhoneNumber(
      auth,
      phoneNumber,
      appVerifier
    );
    verificationId = confirmation.verificationId;

    // Show OTP modal
    const otpModal = new bootstrap.Modal(document.getElementById("otpModal"));
    otpModal.show();

    // Start OTP timer
    startOtpTimer();
  } catch (error) {
    console.error("Error sending OTP:", error);
    Notiflix.Notify.failure("Failed to send OTP. Please try again.");
  }
}

// Function to verify OTP
async function verifyOtp(otp) {
  try {
    const credential = PhoneAuthProvider.credential(verificationId, otp);
    await signInWithCredential(auth, credential);
    return true;
  } catch (error) {
    console.error("Error verifying OTP:", error);
    Notiflix.Notify.failure("Invalid OTP. Please try again.");
    return false;
  }
}
