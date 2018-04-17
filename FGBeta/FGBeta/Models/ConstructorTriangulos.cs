using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Text;

namespace FGBeta.Models
{
    public class ConstructorTriangulos
    {
        #region Atributos
        double ladoA;
        double ladoB;
        double ladoC;
        tipoTriangulo tipo;

        //Valdrá 1 si las medidas ingresadas son válidas con las propiedades de un triángulo.
        //Valdrá -1 si se ingresaron lados incompatible para la formación de un triángulo, y
        //así se sabrá si el triángulo fue corregido por el constructor de triángulos.

        int trianguloPerfecto;

        double anguloAB;
        double anguloBC;
        double anguloCA;

        #endregion

        #region Constructores

        //Si se detecta un valor negativo, se lo convierte a positivo.
        public ConstructorTriangulos(double A, double B, double C)
        {
            if(A < 0)
            {
                A *= -1;
            }
            if (B < 0)
            {
                B *= -1;
            }
            if (C < 0)
            {
                C *= -1;
            }
            this.LadoA = A;
            this.LadoB = B;
            this.LadoC = C;

            this.trianguloPerfecto = this.validadorTriangulo();
            this.AsigarTipo();
        }

        
        #endregion

        #region Propiedades

        public tipoTriangulo Tipo
        {
            get
            {
                return this.tipo;
            }
        }
        public double Perimetro
        {
            get
            {
                return this.CalcularPerimetro();

            }
        }

        public double Area
        {
            get
            {
                return this.CalcularArea();

            }
        }

        public double LadoA
        {
            get
            {
                return this.ladoA;
            }
            set
            {
                this.ladoA = value;
            }

        }

        public double LadoB
        {
            get
            {
                return this.ladoB;
            }
            set
            {
                this.ladoB = value;
            }

        }

        public double LadoC
        {
            get
            {
                return this.ladoC;
            }
            set
            {
                this.ladoC = value;
            }

        }

        public double AnguloAB
        {
            get
            {
                return this.anguloAB;
            }
        }

        public double AnguloBC
        {
            get
            {
                return this.anguloBC;
            }
        }

        public double AnguloCA
        {
            get
            {
                return this.anguloCA;
            }
        }

        #endregion

        #region Métodos
        public int validadorTriangulo()
        {
            int retValue = 1;

            if ((this.ladoA + this.ladoB) < ladoC)
            {
                this.ladoC = this.ladoB + this.ladoA - 1;
                retValue = -1;
            }
            if ((this.ladoB + this.ladoC) < ladoA)
            {
                this.ladoA = this.ladoB + this.ladoC - 1;
                retValue = -1;
            }
            if ((this.ladoC + this.ladoA) < ladoB)
            {
                this.ladoB = this.ladoC + this.ladoA - 1;
                retValue = -1;
            }

            this.anguloAB = this.CalcularAnguloAB();
            this.anguloBC = this.CalcularAnguloBC();
            this.anguloCA = this.CalcularAnguloCA();

            return retValue;


        }

        void AsigarTipo()
        {
            if (this.LadoA == this.LadoB && this.LadoB == this.LadoC)
                this.tipo = tipoTriangulo.Equilatero;
            if (this.LadoA == LadoB && this.LadoB != this.LadoC || this.LadoA == LadoC && this.LadoC != this.LadoB || this.LadoB == LadoC && this.LadoC != this.LadoA)
                this.tipo = tipoTriangulo.Isoceles;
            if (this.LadoA != this.LadoB && this.LadoA != this.LadoC && this.ladoB != this.LadoC)
                this.tipo = tipoTriangulo.Escaleno;
        }

        //Se utiliza el teorema del coseno para calcular los angulos.
        double CalcularAnguloAB()
        {
            double retAngulo = (Math.Acos((Math.Pow(this.ladoC, 2.0) - Math.Pow(this.ladoA, 2.0) - Math.Pow(this.ladoB, 2.0)) / (-2 * ladoA * ladoB))) * 180 / Math.PI;
            return retAngulo;
        }
        double CalcularAnguloBC()
        {
            double retAngulo = (Math.Acos((Math.Pow(this.ladoA, 2.0) - Math.Pow(this.ladoC, 2.0) - Math.Pow(this.ladoB, 2.0)) / (-2 * ladoC * ladoB))) * 180 / Math.PI;
            return retAngulo;
        }
        double CalcularAnguloCA()
        {
            double retAngulo = (Math.Acos((Math.Pow(this.ladoB, 2.0) - Math.Pow(this.ladoC, 2.0) - Math.Pow(this.ladoA, 2.0)) / (-2 * ladoC * ladoA))) * 180 / Math.PI;
            return retAngulo;
        }

        double CalcularArea()
        {
            double semiPerimetro = CalcularPerimetro() / 2;
            double area = Math.Sqrt(semiPerimetro * (semiPerimetro - this.LadoA) * (semiPerimetro - this.LadoB) * (semiPerimetro - this.LadoC));
            return area;
        }

        double CalcularPerimetro()
        {
            return LadoA + LadoB + LadoC;

        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder();

            sb.AppendLine(String.Format("Lado A: {0}cms", this.LadoA));
            sb.AppendLine(String.Format("Lado B: {0}cms", this.LadoB));
            sb.AppendLine(String.Format("Lado C: {0}cms", this.LadoC));
            sb.AppendLine(String.Format("Angulo AB: {0}°", this.anguloAB));
            sb.AppendLine(String.Format("Angulo BC: {0}°", this.anguloBC));
            sb.AppendLine(String.Format("Angulo CA: {0}°", this.anguloCA));
            sb.AppendLine(String.Format("Area Del Triangulo: {0}cms^2", this.Area));
            sb.AppendLine(String.Format("Perimetro Del Triangulo: {0}cms", this.Perimetro));
            if (this.trianguloPerfecto == -1)
            {
                sb.AppendLine("El Triangulo Que Se Muestra Fue Modificado Por El Constructor De Triangulos");
            }

            return sb.ToString();

        }

        #endregion

        #region Enumerados
        public enum tipoTriangulo
        {
            Equilatero,
            Isoceles,
            Escaleno

        };
        #endregion


    }
}