/**
 * SHA-256 hashing for stable fingerprinting.
 */
async function sha256(str) {
  const buffer = new TextEncoder().encode(str);
  const hashBuffer = await crypto.subtle.digest("SHA-256", buffer);
  return Array.from(new Uint8Array(hashBuffer))
    .map((b) => b.toString(16).padStart(2, "0"))
    .join("");
}

/**
 * Normalize values for consistency.
 */
function normalize(value) {
  return String(value).trim().toLowerCase().replace(/\s+/g, " ");
}

/**
 * Screen & Device Fingerprinting.
 */
function getScreenFingerprint() {
  return normalize(
    `${screen.width}x${screen.height}~${screen.colorDepth}~${
      window.devicePixelRatio || "1"
    }`
  );
}

/**
 * Stable WebGL Fingerprinting.
 */
function getWebGLFingerprint() {
  try {
    const canvas = document.createElement("canvas");
    const gl =
      canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
    if (!gl) return "no_webgl";
    const debugInfo = gl.getExtension("WEBGL_debug_renderer_info");
    return debugInfo
      ? normalize(
          gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL) +
            "~" +
            gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL)
        )
      : "unknown_webgl";
  } catch (e) {
    return "no_webgl";
  }
}

/**
 * Timezone Fingerprint (More reliable than offsets).
 */
function getTimezoneFingerprint() {
  return normalize(
    Intl.DateTimeFormat().resolvedOptions().timeZone || "unknown_timezone"
  );
}

/**
 * CPU & Hardware Fingerprint.
 */
function getHardwareFingerprint() {
  return normalize(
    `${navigator.hardwareConcurrency || "unknown"}~${
      navigator.deviceMemory || "unknown"
    }`
  );
}

/**
 * Favicon Caching Trick - Unique to User.
 */
function getFaviconFingerprint() {
  return new Promise((resolve) => {
    const img = new Image();
    img.src = "/favicon.ico?" + Math.random();
    img.onload = () => resolve("favicon_present");
    img.onerror = () => resolve("favicon_missing");
  });
}

/**
 * Collect all fingerprint data.
 */
async function collectFingerprintData() {
  const faviconFP = await getFaviconFingerprint();
  const fingerprintComponents = {
    screenFP: getScreenFingerprint(),
    webglFP: getWebGLFingerprint(),
    timezoneFP: getTimezoneFingerprint(),
    hardwareFP: getHardwareFingerprint(),
    faviconFP: faviconFP,
  };
  return fingerprintComponents;
}

/**
 * Generate a persistent fingerprint.
 */
async function generateFingerprint() {
  const components = await collectFingerprintData();
  const sortedValues = Object.keys(components)
    .sort()
    .map((key) => `${key}:${components[key]}`)
    .join("|");
  return sha256(sortedValues);
}
