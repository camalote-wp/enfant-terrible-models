export const Button = ({ children, onClick, isDestructive, icon, variant, label, isBusy, ...props }: any) => {
  const { __nextHasNoMarginBottom, ...cleanProps } = props;
  const computedAriaLabel = children ? undefined : (label || (typeof icon === 'string' ? icon : undefined));

  return (
    <button 
      onClick={onClick} 
      data-variant={variant}
      data-destructive={isDestructive ? 'true' : 'false'}
      aria-label={computedAriaLabel}
      data-busy={isBusy ? 'true' : 'false'}
      {...cleanProps}
    >
      {children}
    </button>
  );
};

export const TextControl = ({ label, value, onChange, ...props }: any) => {
  const { __nextHasNoMarginBottom, __next40pxDefaultSize, ...cleanProps } = props;
  return (
    <div>
      <label>
        {label}
        <input 
          type="text" 
          value={value} 
          onChange={(e) => onChange(e.target.value)} 
          {...cleanProps}
        />
      </label>
    </div>
  );
};

export const ProgressBar = ({ className }: any) => <div role="progressbar" className={className} />;

export const Snackbar = ({ children, onRemove, className, ...props }: any) => (
    <div className={className} {...props}>
      {children}
      <button onClick={onRemove} data-testid="snackbar-close-button">
        x
      </button>
    </div>
  );

export const Popover = () => null;
export const Slot = () => null;
export const Fill = () => null;

export const PanelBody = ({ children, title }: any) => (
  <div data-testid="panel-body" data-title={title}>{children}</div>
);

export const Placeholder = ({ label, instructions }: any) => (
  <div data-testid="placeholder" data-label={label} data-instructions={instructions} />
);

export const Spinner = () => <div data-testid="spinner" />;