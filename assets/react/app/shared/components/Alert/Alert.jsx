import React from "react";

export default function Alert({ type = "info", message }) {
  if (!message) return null;

  const alertClass = {
    success: "alert alert-success alert-dismissible fade show",
    danger: "alert alert-danger alert-dismissible fade show",
    warning: "alert alert-warning alert-dismissible fade show",
    info: "alert alert-info alert-dismissible fade show",
  }[type] || "alert alert-info";

  return (
    <div className="mx-auto" data-bs-theme="dark">
      <div className={alertClass} role="alert"  data-bs-theme="dark">
        {message}
        <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  );
}