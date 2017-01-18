// Generated by CoffeeScript 1.4.0
(function() {

  describe("Interval", function() {
    var leftIntervalSymbol, rightIntervalSymbol;
    leftIntervalSymbol = new FUNDDY.JsTranslations.IntervalSymbol("[");
    rightIntervalSymbol = new FUNDDY.JsTranslations.IntervalSymbol("]");
    describe("#constructor()", function() {
      it("throws exception if left number is higher than right number", function() {
        return expect(function() {
          return new FUNDDY.JsTranslations.Interval(leftIntervalSymbol, "20", "10", rightIntervalSymbol);
        }).to.throwError();
      });
      return it("throws exception while passing invalid format numbers", function() {
        var invalidNumber, invalidNumbers, _i, _len, _results;
        invalidNumbers = ["a", "", "-"];
        _results = [];
        for (_i = 0, _len = invalidNumbers.length; _i < _len; _i++) {
          invalidNumber = invalidNumbers[_i];
          _results.push(expect(function() {
            return new FUNDDY.JsTranslations.Interval(leftIntervalSymbol, invalidNumber, "10", rightIntervalSymbol);
          }).to.throwError());
        }
        return _results;
      });
    });
    return describe("#contains()", function() {
      var excludedStartAndEndInterval, interval;
      interval = new FUNDDY.JsTranslations.Interval(leftIntervalSymbol, "3", "5", rightIntervalSymbol);
      excludedStartAndEndInterval = new FUNDDY.JsTranslations.Interval(rightIntervalSymbol, "1", "2", leftIntervalSymbol);
      it("returns true if it contains the number", function() {
        return expect(interval.contains(4)).to.be.ok();
      });
      it("returns false if it doesn't contain the number", function() {
        return expect(interval.contains(11)).not.to.be.ok();
      });
      it("doesn't include interval excluded start number", function() {
        return expect(excludedStartAndEndInterval.contains(1)).not.to.be.ok();
      });
      it("doesn't include interval excluded end number", function() {
        return expect(excludedStartAndEndInterval.contains(2)).not.to.be.ok();
      });
      it("doesn't contain a lower number than interval start", function() {
        return expect(interval.contains(2)).not.to.be.ok();
      });
      it("doesn't contain a higher number than interval end", function() {
        return expect(interval.contains(6)).not.to.be.ok();
      });
      it("negative infinity works", function() {
        interval = new FUNDDY.JsTranslations.Interval(leftIntervalSymbol, "-Inf", "1", rightIntervalSymbol);
        return expect(interval.contains(-100)).to.be.ok();
      });
      return it("positive infinity works", function() {
        interval = new FUNDDY.JsTranslations.Interval(leftIntervalSymbol, "1", "Inf", rightIntervalSymbol);
        return expect(interval.contains(100)).to.be.ok();
      });
    });
  });

}).call(this);